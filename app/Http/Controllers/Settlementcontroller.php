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
            $decision = strtoupper($request->decision); // APPROVED / REJECTED

            $settlement->settlement_status = $decision;
            $settlement->approved_by = session('user_id');
            $settlement->approved_at = now();

            $this->appendHistory($settlement, $request->decision, $request->remarks);
            $settlement->save();

            if ($decision === 'APPROVED') {
                // Update employee balance – deduct what company settled
                $balance = EmployeeBalance::firstOrCreate(
                    ['employee_id' => $settlement->employee_id],
                    [
                        'total_iou_amount' => 0,
                        'total_settlement_amount' => 0,
                        'total_claim_amount' => 0,
                        'pending_balance' => 0,
                    ]
                );

                $balance->total_settlement_amount += $settlement->company_settlement_amount;
                $balance->pending_balance -= $settlement->company_settlement_amount;
                if ($balance->pending_balance < 0) {
                    $balance->pending_balance = 0;
                }

                // If employee has extra claim, add it to total_claim_amount
                // if ($settlement->employee_claim_amount > 0) {
                //     $balance->total_claim_amount += $settlement->employee_claim_amount;
                // }
                if ($settlement->employee_claim_amount > 0) {
                    $balance->total_claim_amount += $settlement->employee_claim_amount;
                    $balance->pending_claim_amount += $settlement->employee_claim_amount;
                }
                // dd($balance);
                $balance->save();

                // Settlement transaction record
                MoneyTransaction::create([
                    'employee_id' => $settlement->employee_id,
                    'ticket_id' => $settlement->ticket_id,
                    'reference_id' => $settlement->settlement_id,
                    'type' => 'iou_settlement',
                    'amount' => $settlement->company_settlement_amount,
                    'remarks' => 'Settlement approved. '.$request->remarks,
                    'created_by' => session('user_id'),
                ]);

                // Update ticket status
                IssueTicket::where('ticketId', $settlement->ticket_id)
                    ->update(['Status' => 2]);
            } else {

                IssueTicket::where(
                    'ticketId',
                    $settlement->ticket_id
                )->update([
                    'Status' => 4, // Rejected
                ]);
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

    public function transferClaim(Request $request, $settlementId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string|max:50',
            'remarks' => 'required|string|max:1000',
        ]);

        $settlement = IouSettlement::findOrFail($settlementId);

        if ($settlement->settlement_status !== 'APPROVED') {
            return response()->json(['message' => 'Only APPROVED settlements allow claim transfer.'], 422);
        }

        if ($settlement->employee_claim_amount <= 0) {
            return response()->json(['message' => 'No extra claim amount for this settlement.'], 422);
        }

        DB::beginTransaction();
        try {
            // Record the transfer transaction
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

            $balance = EmployeeBalance::where(
                'employee_id',
                $settlement->employee_id
            )->first();
            if ($balance) {
                $balance->pending_claim_amount =
                    ($balance->pending_claim_amount ?? 0)
                    - $request->amount;
                if ($balance->pending_claim_amount < 0) {
                    $balance->pending_claim_amount = 0;
                }
                $balance->save();
            }

            $remainingClaim = $settlement->employee_claim_amount - $settlement->claim_transfer_amount;
            // if ($request->amount > $remainingClaim) {
            //     return response()->json([
            //         'message' => 'Transfer exceeds pending claim amount.',
            //     ], 422);
            // }
            if ($remainingClaim <= 0) {
                $settlement->settlement_status = 'CLOSED';
                IssueTicket::where(
                    'ticketId',
                    $settlement->ticket_id
                )->update([
                    'Status' => 3,
                ]);

                $this->appendHistory(
                    $settlement,
                    'closed',
                    'Settlement auto closed after claim transfer completed.'
                );
            }
            $this->appendHistory(
                $settlement,
                'transferred',
                'Claim ₹'.number_format($request->amount, 2).' transferred to employee. '.$request->remarks
            );
            $settlement->save();

            DB::commit();

            return response()->json(['message' => 'Claim amount transferred to employee successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Transfer failed: '.$e->getMessage()], 500);
        }
    }

    // public function recordReturn(Request $request, $settlementId)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:0.01',
    //         'payment_mode' => 'required|string|max:50',
    //         'remarks' => 'required|string|max:1000',
    //     ]);

    //     $settlement = IouSettlement::findOrFail($settlementId);

    //     if ($settlement->settlement_status !== 'APPROVED') {
    //         return response()->json(['message' => 'Only APPROVED settlements allow recording a return.'], 422);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Update employee balance – add returned amount back
    //         $balance = EmployeeBalance::where('employee_id', $settlement->employee_id)->first();
    //         if ($balance) {
    //             $balance->pending_balance -= $request->amount; // reduce what they owe
    //             if ($balance->pending_balance < 0) {
    //                 $balance->pending_balance = 0;
    //             }
    //             $balance->save();
    //         }

    //         // Record return transaction
    //         MoneyTransaction::create([
    //             'employee_id' => $settlement->employee_id,
    //             'ticket_id' => $settlement->ticket_id,
    //             'reference_id' => $settlement->settlement_id,
    //             'type' => 'cash_returned',
    //             'amount' => $request->amount,
    //             'payment_mode' => $request->payment_mode,
    //             'remarks' => $request->remarks.' [Mode: '.$request->payment_mode.']',
    //             'created_by' => session('user_id'),
    //         ]);

    //         $this->appendHistory(
    //             $settlement,
    //             'cash_returned',
    //             'Employee returned ₹'.number_format($request->amount, 2).'. '.$request->remarks
    //         );
    //         $settlement->save();

    //         DB::commit();

    //         return response()->json(['message' => 'Cash return recorded successfully.']);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         return response()->json(['message' => 'Record return failed: '.$e->getMessage()], 500);
    //     }
    // }

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
