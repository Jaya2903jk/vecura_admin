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
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function view($ticketId)
    {
        $ticket = IssueTicket::with(['department', 'location', 'customer'])
            ->where('ticketId', $ticketId)
            ->first();

        if (! $ticket) {
            return redirect()->route('tickets')->with('error', 'Ticket not found.');
        }

        if ($ticket->type == 'Settlement') {
            $settlement = IouSettlement::with(['employee', 'items', 'approver', 'creator'])
                ->where('ticket_id', $ticketId)
                ->first();

            if (! $settlement) {
                return redirect()->route('ticket.view', $ticketId);
            }

            $paymentTransactions = MoneyTransaction::where('reference_id', $settlement->settlement_id)
                ->whereIn('type', ['claim_transfer', 'cash_returned'])
                ->with('creator')
                ->orderByDesc('created_at')
                ->get();
            $balance = EmployeeBalance::where('employee_id', $settlement->employee_id)->first();

            $transactions = MoneyTransaction::with('creator')
                ->where('ticket_id', $ticketId)
                ->orderBy('created_at', 'asc')
                ->get();
            $claimTransfers = MoneyTransaction::with('creator')
                ->where('ticket_id', $ticketId)
                ->where('type', 'claim_transfer')
                ->orderBy('created_at', 'asc')
                ->get();

            $metaData = $settlement->meta_data ?? [];
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
                        'changedBy' => $employee->FullName ?? $employee->UserName ?? 'Unknown',
                        'designation' => $employee->designation->Designation ?? '-',
                        'changedAt' => $item['date'] ?? null,
                    ];
                });

            return view('ticket.view_settlement', compact(
                'ticket','paymentTransactions',
                'settlement',
                'balance',
                'transactions',
                'claimTransfers',
                'actionHistory'
            ));
        } else {
            $iou = IouRequest::with([
                'transactions',
                'claims',
                'employee',
                'department',
                'category',
                'issue',
            ])
                ->where('ticket_id', $ticketId)
                ->first();

            if (! $iou) {
                return redirect()->route('ticket.view', $ticketId);
            }

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

    private function updateEmployeeBalance(int $employeeId): void
    {
        $totalIou = IouRequest::where('employee_id', $employeeId)
            ->whereIn('status', ['approved', 'paid', 'settled', 'closed'])
            ->sum('approved_amount');

        // $totalSettled = IouSettlement::where('employee_id', $employeeId)
        //     ->sum('actual_expense');

        $totalClaim = ClaimRequest::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->sum('expense_amount');

        // $pending = $totalIou - $totalSettled - $totalClaim;
        $pending = $totalIou - $totalClaim;

        EmployeeBalance::updateOrCreate(
            ['employee_id' => $employeeId],
            [
                'total_iou_amount' => $totalIou,
                // 'total_settlement_amount' => $totalSettled,
                'total_claim_amount' => $totalClaim,
                'pending_balance' => $pending,
            ]
        );
    }
}
