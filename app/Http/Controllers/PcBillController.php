<?php

namespace App\Http\Controllers;

use App\Models\PcBillSubmission;
use App\Models\PcBillItem;
use App\Models\BranchWallet;
use App\Models\WalletTransaction;
use App\Models\UserMaster;
use App\Models\IssueCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PcBillController extends Controller
{
    // ── View ──────────────────────────────────────────────────────────────
    public function view($ticketId)
    {
        $loginUserId = session('user_id');
        $roleId      = session('role_id');

        $submission = PcBillSubmission::with([
            'branch',
            'wallet',
            'raisedBy',
            'raisedBy.designation',
            'items',
            'items.expense',   // ← expense name load
        ->where('ticket_id', $ticketId)
        ->first();

        if (! $submission) {
            return redirect()->route('ticket.view', $ticketId);
        }

        $ticket = DB::connection('sqlsrv')
            ->table('issueTicket')
            ->where('ticketId', $ticketId)
            ->first();
        $meta          = $submission->meta_data ?? [];
        $statusHistory = $meta['history'] ?? [];

        $actionHistory = collect($statusHistory)->map(function($item) {
            $employee = UserMaster::with('designation')
                ->where('UserID', $item['changed_by'] ?? null)
                ->first();
            return (object) [
                'action'      => $item['action']       ?? '-',
                'remarks'     => $item['remarks']       ?? '-',
                'changedBy'   => $employee
                                 ? ($employee->FullName ?? $employee->UserName ?? 'Unknown')
                                 : ($item['changed_name'] ?? 'Unknown'),
                'designation' => $employee?->designation?->Designation ?? '-',
                'changedAt'   => $item['date']          ?? null,
            ];
        });

        $isAdmin    = in_array($roleId, [13 , 2]);
        $isAccounts = $roleId == 5;
        $isCreator  = $loginUserId == $submission->raised_by;

        return view('ticket.view_pc_bill', compact(
            'submission',
            'ticket',
            'actionHistory',
            'isAdmin',
            'isAccounts',
            'isCreator'
        ));
    }

    public function updateStatus(Request $request, $submissionId)
    {
        $loginUserId = session('user_id');
        $user        = UserMaster::find($loginUserId);

        $request->validate([
            'accounts_status' => 'required|in:open,under_review,approved,rejected,on_hold',
            'remarks'         => 'nullable|string|max:300',
        ]);

        $submission = PcBillSubmission::findOrFail($submissionId);
        $meta    = $submission->meta_data ?? [];
        $history = $meta['history'] ?? [];

        $history[] = [
            'action'       => strtoupper($request->accounts_status),
            'remarks'      => $request->remarks ?? '',
            'changed_by'   => $loginUserId,
            'changed_name' => $user->UserName ?? '',
            'date'         => now()->toDateTimeString(),
        ];

        $submission->accounts_status = $request->accounts_status;
        $submission->meta_data       = ['history' => $history];
        $submission->updated_at      = now();

        $issueTicketStatus = match($request->accounts_status) {
            'open'         => 0,
            'under_review' => 1,
            'on_hold'      => 1,
            'approved'     => 2,
            'rejected'     => 3,
            default        => 0,
        };

        // ── APPROVED → debit wallet + close ───────────────────────────
        if ($request->accounts_status === 'approved') {

            $submission->approved_amount = $submission->total_bill_amount;
            $submission->reviewed_by     = $loginUserId;
            $submission->reviewed_at     = now();
            $submission->ticket_status   = 'closed';
            $submission->closed_at       = now();
            $issueTicketStatus           = 3;

            $wallet = BranchWallet::where('branch_id', $submission->branch_id)->first();

            if ($wallet) {
                $balanceBefore           = $wallet->current_balance;
                $balanceAfter            = max(0, $balanceBefore - $submission->total_bill_amount);
                $wallet->current_balance = $balanceAfter;
                $wallet->total_debited   = $wallet->total_debited + $submission->total_bill_amount;
                $wallet->last_updated    = now();
                $wallet->save();

                WalletTransaction::create([
                    'wallet_id'      => $wallet->wallet_id,
                    'branch_id'      => $submission->branch_id,
                    'direction'      => 'D',
                    'source_type'    => 'pc_bill_submission',
                    'source_id'      => $submissionId,
                    'amount'         => $submission->total_bill_amount,
                    'balance_before' => $balanceBefore,
                    'balance_after'  => $balanceAfter,
                    'narration'      => 'Bill Submission #' . $submissionId . ' approved & debited',
                    'created_by'     => $loginUserId,
                    'created_at'     => now(),
                ]);

                // Update all items to approved
                PcBillItem::where('submission_id', $submissionId)
                    ->update(['item_status' => 'approved']);
            }
        }

        // ── REJECTED → close ──────────────────────────────────────────
        if ($request->accounts_status === 'rejected') {
            $submission->rejection_reason = $request->remarks;
            $submission->ticket_status    = 'closed';
            $submission->closed_at        = now();
            $issueTicketStatus            = 3;

            // Update all items to rejected
            PcBillItem::where('submission_id', $submissionId)
                ->update(['item_status' => 'rejected']);
        }

        // ── Update issueTicket ────────────────────────────────────────
        DB::connection('sqlsrv')
            ->table('issueTicket')
            ->where('ticketId', $submission->ticket_id)
            ->update(['Status' => $issueTicketStatus]);

        $submission->save();

        return response()->json([
            'status'  => true,
            'message' => 'Status updated successfully',
        ]);
    }

    // ── Expense Master API ────────────────────────────────────────────────
    public function getExpenseMaster()
    {
        $expenses = DB::table('expense_master')
            ->where('Status', 1)
            ->select('ExpenseId', 'ExpenseName')
            ->orderBy('ExpenseName')
            ->get();

        return response()->json(['data' => $expenses]);
    }
}
