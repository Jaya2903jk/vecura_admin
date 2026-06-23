<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBalance;
use App\Models\IouSettlement;
use App\Models\IssueTicket;
use App\Models\MoneyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettlementController extends Controller
{
    public function review(Request $request, $settlementId)
    {
        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'remarks' => 'required|string|max:1000',
        ]);

        $settlement = IouSettlement::findOrFail($settlementId);

        if ($settlement->settlement_status !== 'PENDING') {
            return response()->json(['message' => 'Only PENDING settlements can be reviewed.'], 422);
        }

        DB::beginTransaction();
        try {
            $decision = strtoupper($request->decision);
            $settlement->settlement_status = $decision;
            $settlement->approved_by = session('user_id');
            $settlement->approved_at = now();

            $this->appendHistory($settlement, $request->decision, $request->remarks);
            $settlement->save();

            if ($decision === 'APPROVED') {
                $balance = EmployeeBalance::firstOrCreate(
                    ['employee_id' => $settlement->employee_id],
                    [
                        'total_iou_amount' => 0,
                        'total_settlement_amount' => 0,
                        'total_claim_amount' => 0,
                        'pending_balance' => 0,
                        'pending_claim_amount' => 0,
                    ]
                );

                $balance->total_settlement_amount += $settlement->company_settlement_amount;
                $balance->pending_balance -= $settlement->company_settlement_amount;
                if ($balance->pending_balance < 0) {
                    $balance->pending_balance = 0;
                }

                if ($settlement->employee_claim_amount > 0) {
                    $balance->total_claim_amount += $settlement->employee_claim_amount;
                    $balance->pending_claim_amount += $settlement->employee_claim_amount;
                }
                $balance->save();

                MoneyTransaction::create([
                    'employee_id' => $settlement->employee_id,
                    'ticket_id' => $settlement->ticket_id,
                    'reference_id' => $settlement->settlement_id,
                    'type' => 'iou_settlement',
                    'amount' => $settlement->company_settlement_amount,
                    'remarks' => 'Settlement approved. '.$request->remarks,
                    'created_by' => session('user_id'),
                ]);

                // Nothing owed in either direction (bill exactly matched
                // the IOU amount) — close immediately, no payment step needed.
                if ($settlement->employee_claim_amount <= 0 && $settlement->remaining_balance <= 0) {
                    $settlement->settlement_status = 'CLOSED';
                    $this->appendHistory($settlement, 'closed', 'Auto-closed — bill exactly matched the IOU amount, nothing to pay or receive.');
                    $settlement->save();

                    IssueTicket::where('ticketId', $settlement->ticket_id)->update(['Status' => 3]);
                } else {
                    IssueTicket::where('ticketId', $settlement->ticket_id)->update(['Status' => 2]);
                }
            } else {
                IssueTicket::where('ticketId', $settlement->ticket_id)->update(['Status' => 4]);
            }

            DB::commit();

            return response()->json([
                'message' => $decision === 'APPROVED'
                    ? 'Settlement approved successfully.'
                    : 'Settlement rejected.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Action failed: '.$e->getMessage()], 500);
        }
    }

    /**
     * SINGLE unified action for both money directions:
     *   direction = 'pay_employee'     -> company transfers the employee's
     *                                      extra claim amount to them.
     *   direction = 'receive_employee' -> employee returns unused cash to
     *                                      the company.
     *
     * Either direction moves the settlement to RECEIVED. This replaces
     * the old separate transferClaim() / recordReturn() methods — one
     * model, one status, one dropdown picks which way the money moves.
     */
    public function processPayment(Request $request, $settlementId)
    {
        $request->validate([
            'direction' => 'required|in:pay_employee,receive_employee',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string|max:50',
            'remarks' => 'required|string|max:1000',
        ]);

        $settlement = IouSettlement::findOrFail($settlementId);

        if (! in_array($settlement->settlement_status, ['APPROVED', 'RECEIVED'])) {
            return response()->json(['message' => 'Only APPROVED or RECEIVED settlements allow processing a payment.'], 422);
        }

        $direction = $request->direction;

        DB::beginTransaction();
        try {
            if ($direction === 'pay_employee') {
                $remainingClaim = $settlement->employee_claim_amount - ($settlement->claim_transfer_amount ?? 0);

                if ($remainingClaim <= 0) {
                    DB::rollBack();

                    return response()->json(['message' => 'No extra claim amount remaining for this settlement.'], 422);
                }
                if ($request->amount > $remainingClaim) {
                    DB::rollBack();

                    return response()->json([
                        'message' => 'Amount (₹'.number_format($request->amount, 2).') exceeds the remaining claim (₹'.number_format($remainingClaim, 2).').',
                    ], 422);
                }

                MoneyTransaction::create([
                    'employee_id' => $settlement->employee_id,
                    'ticket_id' => $settlement->ticket_id,
                    'reference_id' => $settlement->settlement_id,
                    'type' => 'claim_transfer',
                    'amount' => $request->amount,
                    'payment_mode' => $request->payment_mode,
                    'remarks' => $request->remarks.' [Mode: '.$request->payment_mode.']',
                    'created_by' => session('user_id'),
                ]);

                $settlement->claim_transfer_amount = ($settlement->claim_transfer_amount ?? 0) + $request->amount;

                $balance = EmployeeBalance::where('employee_id', $settlement->employee_id)->first();
                if ($balance) {
                    $balance->pending_claim_amount = max(0, ($balance->pending_claim_amount ?? 0) - $request->amount);
                    $balance->save();
                }

                $historyNote = 'Paid ₹'.number_format($request->amount, 2).' to employee (extra claim). '.$request->remarks;
            } else {
                // receive_employee
                $remainingReturn = $settlement->remaining_balance ?? 0;

                if ($remainingReturn <= 0) {
                    DB::rollBack();

                    return response()->json(['message' => 'No return amount pending for this settlement.'], 422);
                }
                if ($request->amount > $remainingReturn) {
                    DB::rollBack();

                    return response()->json([
                        'message' => 'Amount (₹'.number_format($request->amount, 2).') exceeds the pending return (₹'.number_format($remainingReturn, 2).').',
                    ], 422);
                }

                MoneyTransaction::create([
                    'employee_id' => $settlement->employee_id,
                    'ticket_id' => $settlement->ticket_id,
                    'reference_id' => $settlement->settlement_id,
                    'type' => 'cash_returned',
                    'amount' => $request->amount,
                    'payment_mode' => $request->payment_mode,
                    'remarks' => $request->remarks.' [Mode: '.$request->payment_mode.']',
                    'created_by' => session('user_id'),
                ]);

                $settlement->remaining_balance = max(0, $remainingReturn - $request->amount);

                $balance = EmployeeBalance::where('employee_id', $settlement->employee_id)->first();
                if ($balance) {
                    $balance->pending_balance = max(0, $balance->pending_balance - $request->amount);
                    $balance->save();
                }

                $historyNote = 'Received ₹'.number_format($request->amount, 2).' from employee (cash return). '.$request->remarks;
            }

            // Either direction lands on the same status: RECEIVED.
            if ($settlement->settlement_status !== 'RECEIVED') {
                $settlement->settlement_status = 'RECEIVED';

                // IssueTicket::where('ticketId', $settlement->ticket_id)
                //     ->update(['Status' => 5]); // Received

                $this->appendHistory($settlement, 'received', 'Settlement marked Received — payment processed, pending final close confirmation.');
            }

            $this->appendHistory($settlement, $direction === 'pay_employee' ? 'paid_employee' : 'received_from_employee', $historyNote);
            $settlement->save();

            DB::commit();

            return response()->json([
                'message' => $direction === 'pay_employee'
                    ? 'Payment to employee recorded. Settlement is now Received — close it once confirmed.'
                    : 'Cash receipt from employee recorded. Settlement is now Received — close it once confirmed.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Action failed: '.$e->getMessage()], 500);
        }
    }

    /**
     * Final close — the only place settlement_status becomes CLOSED for
     * settlements that needed a payment step. (The "nothing owed" case
     * is auto-closed directly inside review().)
     */
    public function close(Request $request, $settlementId)
    {
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $settlement = IouSettlement::findOrFail($settlementId);

        if (! in_array($settlement->settlement_status, ['APPROVED', 'RECEIVED'])) {
            return response()->json(['message' => 'Only APPROVED or RECEIVED settlements can be closed.'], 422);
        }

        DB::beginTransaction();
        try {
            $settlement->settlement_status = 'CLOSED';

            IssueTicket::where('ticketId', $settlement->ticket_id)->update(['Status' => 3]);

            $this->appendHistory($settlement, 'closed', $request->remarks);
            $settlement->save();

            DB::commit();

            return response()->json(['message' => 'Settlement closed successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Close failed: '.$e->getMessage()], 500);
        }
    }

    private function appendHistory(IouSettlement $settlement, string $action, string $remarks): void
    {
        $meta = $settlement->meta_data ?? [];
        if (is_string($meta)) {
            $meta = json_decode($meta, true) ?? [];
        }

        if (! isset($meta['history'])) {
            $meta['history'] = [];
        }

        $meta['history'][] = [
            'action' => $action,
            'remarks' => $remarks,
            'user_id' => session('user_id'),
            'date' => now()->toDateTimeString(),
        ];

        $settlement->meta_data = $meta;
    }
}
