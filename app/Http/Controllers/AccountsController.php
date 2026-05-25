<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use App\Models\EmployeeBalance;
use App\Models\IouRequest;
use App\Models\IouSettlement;
use App\Models\IssueTicket;
use App\Models\MoneyTransaction;
use App\Models\UserMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function view($ticketId)
    {
        $ticket = IssueTicket::with(['department', 'location', 'customer'])
            ->where('ticketId', $ticketId)
            ->firstOrFail();

        $iou = IouRequest::with([
            'transactions',
            'settlements',
            'claims',
            'employee',
            'department',
            'category',
            'issue',
        ])
            ->where('ticket_id', $ticketId)
            ->firstOrFail();

        $balance = EmployeeBalance::where('employee_id', $iou->employee_id)->first();
        $metaData = $iou->meta_data ?? [];
        if (is_string($metaData)) {
            $metaData = json_decode($metaData, true);
        }
        $actionHistory = collect($metaData['history'] ?? [])
            ->map(function ($item) {

                $employee = UserMaster::with('designation')
                    ->where('UserID', $item['user_id'] ?? null)
                    ->first();

                return (object) [

                    'status' => $item['action'] ?? '-',

                    'remarks' => $item['remarks'] ?? '-',

                    'changedBy' => $employee->FullName
                        ?? $employee->UserName
                        ?? 'Unknown',

                    'designation' => $employee->designation->Designation
                        ?? '-',

                    'changedAt' => $item['date'] ?? null,
                ];
            });

        return view('ticket.view_accounts', compact('iou', 'balance', 'ticket', 'actionHistory'));
    }

    public function approve(Request $request, $iouId)
    {
        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'remarks' => 'required|string|max:1000',
            'approved_amount' => 'required_if:decision,approved|nullable|numeric|min:1',
        ]);

        $iou = IouRequest::findOrFail($iouId);

        if ($iou->status !== 'pending') {
            return response()->json(['message' => 'Only Pending requests can be reviewed.'], 422);
        }

        DB::beginTransaction();
        try {
            if ($request->decision === 'approved') {
                $iou->status = 'approved';
                $iou->approved_amount = $request->approved_amount;
                $iou->approved_by = session('user_id');
                $iou->approved_at = now();
                $iou->remarks = $request->remarks;
                $this->updateMetaData(
                    $iou,
                    'approval',
                    [
                        'decision' => 'approved',
                        'approved_amount' => $request->approved_amount,
                        'approved_by' => session('user_id'),
                        'approved_at' => now(),
                    ],
                    [
                        'action' => 'approved',
                        'remarks' => $request->remarks,
                        'user_id' => session('user_id'),
                        'date' => now(),
                    ]
                );
            } else {
                $iou->status = 'rejected';
                $iou->remarks = $request->remarks;
                $this->updateMetaData(
                    $iou,
                    'rejection',
                    [
                        'decision' => 'rejected',
                        'rejected_by' => session('user_id'),
                        'rejected_at' => now(),
                    ],
                    [
                        'action' => 'rejected',
                        'remarks' => $request->remarks,
                        'user_id' => session('user_id'),
                        'date' => now(),
                    ]
                );
            }

            $iou->save();
            IssueTicket::where('ticketId', $iou->ticket_id)
                ->update([
                    'Status' => 1,
                    // 'ModifiedBy' => session('user_id'),
                    // 'ModifiedDate' => now(),
                ]);
            DB::commit();

            return response()->json([
                'message' => $request->decision === 'approved'
                    ? 'IOU request approved successfully.'
                    : 'IOU request rejected.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Action failed: '.$e->getMessage()], 500);
        }
    }

    private function updateMetaData(
        $model,
        $metaKey,
        array $metaValues = [],
        array $historyValues = []
    ) {
        $meta = $model->meta_data ?? [];
        $oldMeta = $meta[$metaKey] ?? [];
        $meta[$metaKey] = array_merge($oldMeta, $metaValues);
        if (! isset($meta['history'])) {
            $meta['history'] = [];
        }
        $meta['history'][] = $historyValues;
        $model->meta_data = $meta;

        return $model;
    }

    public function pay(Request $request, $iouId)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|string|max:50',
            'remarks' => 'required|string|max:1000',
        ]);
        $iou = IouRequest::findOrFail($iouId);
        if ($iou->status !== 'approved') {
            return response()->json([
                'message' => 'Only Approved requests can be marked as Paid.',
            ], 422);
        }
        DB::beginTransaction();
        try {
            $iou->status = 'closed'; // Directly close
            $iou->paid_amount = $request->paid_amount;
            $iou->paid_by = session('user_id');
            $iou->paid_at = now();

            $iou->remarks = $iou->remarks
                ? $iou->remarks."\n[Paid & Closed] ".$request->remarks
                : $request->remarks;

            /*
            |--------------------------------------------------------------------------
            | Update Metadata
            |--------------------------------------------------------------------------
            */

            $this->updateMetaData(
                $iou,
                'payment',
                [
                    'decision' => 'paid',
                    'paid_amount' => $request->paid_amount,
                    'payment_mode' => $request->payment_mode,
                    'paid_by' => session('user_id'),
                    'paid_at' => now(),
                ],
                [
                    'action' => 'paid',
                    'remarks' => $request->remarks,
                    'user_id' => session('user_id'),
                    'date' => now(),
                ]
            );
            $this->updateMetaData(
                $iou,
                'closure',
                [
                    'decision' => 'closed',
                    'closed_by' => session('user_id'),
                    'closed_at' => now(),
                ],
                [
                    'action' => 'closed',
                    'remarks' => 'IOU closed after payment',
                    'user_id' => session('user_id'),
                    'date' => now(),
                ]
            );

            $iou->save();
            MoneyTransaction::create([
                'employee_id' => $iou->employee_id,
                'ticket_id' => $iou->ticket_id,
                'reference_id' => $iou->iou_id,
                'type' => 'iou_paid',
                'amount' => $request->paid_amount,
                'remarks' => $request->remarks.
                    ' [Mode: '.$request->payment_mode.']',
                'created_by' => session('user_id'),
            ]);
            $ticket = IssueTicket::where(
                'ticketId',
                $iou->ticket_id
            )->first();

            if ($ticket) {
                $ticket->status = 3; // Closed
                $ticket->save();
            }
            $this->updateEmployeeBalance($iou->employee_id);

            DB::commit();

            return response()->json([
                'message' => 'Payment completed and ticket closed successfully.',
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Payment failed: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getEmployeeIouBalance(Request $request)
    {
        $employeeId = $request->employee_id;

        $balance = EmployeeBalance::where(
            'employee_id',
            $employeeId
        )->first();
        // dd($balance);

        return response()->json([
            'balance' => number_format(
                $balance->pending_balance ?? 0,
                2,
                '.',
                ''
            ),
        ]);
    }

    public function settle(Request $request, $iouId)
    {
        $request->validate([
            'settlement_date' => 'required|date',
            'actual_expense' => 'required|numeric|min:0',
            'returned_amount' => 'nullable|numeric|min:0',
            'extra_claim_amount' => 'nullable|numeric|min:0',
            'remarks' => 'required|string|max:1000',
        ]);

        $iou = IouRequest::findOrFail($iouId);

        if ($iou->status !== 'paid') {
            return response()->json(['message' => 'Settlement can only be added for Paid requests.'], 422);
        }

        DB::beginTransaction();
        try {
            $returned = $request->returned_amount ?? 0;
            $extra = $request->extra_claim_amount ?? 0;

            // Create settlement record
            $settlement = IouSettlement::create([
                'iou_id' => $iou->iou_id,
                'ticket_id' => $iou->ticket_id,
                'employee_id' => $iou->employee_id,
                'settlement_date' => $request->settlement_date,
                'actual_expense' => $request->actual_expense,
                'returned_amount' => $returned,
                'extra_claim_amount' => $extra,
                'remarks' => $request->remarks,
                'created_by' => session('user_id'),
                'created_at' => now(),
            ]);

            // Update IOU
            $iou->status = 'settled';
            $iou->settlement_amount = $request->actual_expense;
            $iou->settlement_date = $request->settlement_date;

            // Pending balance = paid - actual_expense (positive means employee owes back)
            $iou->pending_balance = ($iou->paid_amount ?? 0) - $request->actual_expense;
            $iou->save();

            // Transaction: returned amount (employee returns money back)
            if ($returned > 0) {
                MoneyTransaction::create([
                    'employee_id' => $iou->employee_id,
                    'ticket_id' => $iou->ticket_id,
                    'reference_id' => $settlement->settlement_id,
                    'type' => 'refund',
                    'amount' => $returned,
                    'remarks' => 'Employee returned amount on settlement',
                    'created_by' => Auth::id(),
                ]);
            }

            // Transaction: extra claim (company owes employee)
            if ($extra > 0) {
                MoneyTransaction::create([
                    'employee_id' => $iou->employee_id,
                    'ticket_id' => $iou->ticket_id,
                    'reference_id' => $settlement->settlement_id,
                    'type' => 'claim',
                    'amount' => $extra,
                    'remarks' => 'Extra claim by employee on settlement',
                    'created_by' => Auth::id(),
                ]);
            }

            // Update employee balance
            $this->updateEmployeeBalance($iou->employee_id);

            DB::commit();

            return response()->json(['message' => 'Settlement recorded successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Settlement failed: '.$e->getMessage()], 500);
        }
    }

    public function close(Request $request, $iouId)
    {
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $iou = IouRequest::findOrFail($iouId);

        $ticket = IssueTicket::where('ticketId', $iou->ticket_id)->firstOrFail();
        if ($iou->status !== 'settled') {
            return response()->json(['message' => 'Only Settled requests can be closed.'], 422);
        }
        // dd($ticket);
        DB::beginTransaction();
        try {
            $iou->status = 'closed';
            $iou->remarks = $iou->remarks
                ? $iou->remarks."\n[Closed] ".$request->remarks
                : $request->remarks;
            $iou->save();
            $ticket->status = 3; // final state
            // $ticket->remarks = $request->remarks;
            $ticket->save();
            DB::commit();

            return response()->json(['message' => 'IOU ticket closed successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => 'Close failed: '.$e->getMessage()], 500);
        }
    }

    private function updateEmployeeBalance(int $employeeId): void
    {
        $totalIou = IouRequest::where('employee_id', $employeeId)
            ->whereIn('status', ['approved', 'paid', 'settled', 'closed'])
            ->sum('approved_amount');

        $totalSettled = IouSettlement::where('employee_id', $employeeId)
            ->sum('actual_expense');

        $totalClaim = ClaimRequest::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->sum('expense_amount');

        $pending = $totalIou - $totalSettled - $totalClaim;

        EmployeeBalance::updateOrCreate(
            ['employee_id' => $employeeId],
            [
                'total_iou_amount' => $totalIou,
                'total_settlement_amount' => $totalSettled,
                'total_claim_amount' => $totalClaim,
                'pending_balance' => $pending,
            ]
        );
    }
}
