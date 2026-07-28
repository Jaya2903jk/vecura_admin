<?php

namespace App\Http\Controllers;

use App\Models\PcRequest;
use App\Models\BranchWallet;
use App\Models\UserMaster;
use App\Models\IssueTicket;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PcRequestController extends Controller
{
    // ── View ──────────────────────────────────────────────────────────────
   public function view($ticketId)
{
    $loginUserId = session('user_id');
    $roleId      = session('role_id');

    // ── PC Request with relations ─────────────────────────────────────
    $pcRequest = PcRequest::with([
        'branch',
        'wallet',
        'raisedBy',
        'raisedBy.designation',
    ])
    ->where('ticket_id', $ticketId)
    ->first();

    if (! $pcRequest) {
        return redirect()->route('ticket.view', $ticketId);
    }

    // ── Ticket from SQL Server ────────────────────────────────────────
    $ticket = DB::connection('sqlsrv')
        ->table('issueTicket')
        ->where('ticketId', $ticketId)
        ->first();

    $statusHistory = $pcRequest->meta_data['history'] ?? [];

    $actionHistory = collect($statusHistory)
        ->map(function ($item) {
            $employee = UserMaster::with('designation')
                ->where('UserID', $item['changed_by'] ?? null)
                ->first();

            return (object) [
                'action'      => $item['action']       ?? '-',
                'remarks'     => $item['remarks']      ?? '-',
                'changedBy'   => $employee
                             ? ($employee->FullName ?? $employee->UserName ?? $item['changed_name'] ?? 'Unknown')
                             : ($item['changed_name'] ?? 'Unknown'),
                'designation' => $employee->designation->Designation ?? '-',
                'changedAt'   => $item['date']         ?? null,
            ];
        });

    // ── Roles ─────────────────────────────────────────────────────────
    $isAdmin    = in_array($roleId, [13, 2]);
    $isAccounts = $roleId == 5;
    $isCreator  = $loginUserId == $pcRequest->raised_by;

    return view('ticket.view_pettycash', compact(
        'pcRequest',
        'ticket',
        'actionHistory',
        'isAdmin',
        'isAccounts',
        'isCreator',
        'statusHistory',
    ));
}

public function updateStatus(Request $request, $requestId)
{
    $loginUserId = session('user_id');
    $user        = UserMaster::find($loginUserId);

    $request->validate([
        'accounts_status' => 'required|in:open,under_review,approved,transferred,rejected,on_hold,closed',
        'remarks'         => 'nullable|string|max:300',
        'transfer_ref'    => 'required_if:accounts_status,transferred|nullable|string|max:100',
    ]);

    $pcRequest = PcRequest::findOrFail($requestId);

    $meta    = $pcRequest->meta_data ?? [];
    $history = $meta['history'] ?? [];

    $history[] = [
        'action'       => strtoupper($request->accounts_status),
        'remarks'      => $request->remarks ?? '',
        'changed_by'   => $loginUserId,
        'changed_name' => $user->UserName ?? '',
        'date'         => now()->toDateTimeString(),
    ];

    $pcRequest->accounts_status = $request->accounts_status;
    $pcRequest->meta_data       = ['history' => $history];
    $pcRequest->updated_at      = now();

    $issueTicketStatus = match($request->accounts_status) {
        'open'         => 0,
        'under_review' => 1,
        'approved'     => 1,
        'on_hold'      => 1,
        'transferred'  => 2,
        'rejected'     => 3,
        'closed'       => 2,
        default        => 0,
    };

    if ($request->accounts_status === 'approved') {
        $pcRequest->approved_amount = $pcRequest->requested_amount;
        $pcRequest->mgmt_status     = 'approved';
        $issueTicketStatus          = 1;
    }
    if ($request->accounts_status === 'transferred') {

        if (empty($request->transfer_ref)) {
            return response()->json([
                'status'  => false,
                'message' => 'Transfer Reference is required for transferred status.',
            ], 422);
        }

        $pcRequest->approved_amount = $pcRequest->requested_amount;
        $pcRequest->transfer_ref    = $request->transfer_ref;
        $pcRequest->transferred_at  = now();
        $pcRequest->mgmt_status     = 'transferred';
        $pcRequest->ticket_status   = 'closed';
        $pcRequest->closed_at       = now();
        $issueTicketStatus          = 3;

        // Get or create wallet
        $wallet = BranchWallet::where('branch_id', $pcRequest->branch_id)->first();

        if ($wallet) {
            $balanceBefore           = $wallet->current_balance;
            $balanceAfter            = $balanceBefore + $pcRequest->requested_amount;
            $wallet->current_balance = $balanceAfter;
            $wallet->total_credited  = $wallet->total_credited + $pcRequest->requested_amount;
            $wallet->last_updated    = now();
            $wallet->save();
        } else {
            // First time — create wallet
            $balanceBefore = 0;
            $balanceAfter  = $pcRequest->requested_amount;

            $wallet = BranchWallet::create([
                'branch_id'       => $pcRequest->branch_id,
                'current_balance' => $balanceAfter,
                'total_credited'  => $balanceAfter,
                'total_debited'   => 0.00,
                'last_updated'    => now(),
            ]);
        }

        $pcRequest->wallet_id = $wallet->wallet_id;

        WalletTransaction::create([
            'wallet_id'      => $wallet->wallet_id,
            'branch_id'      => $pcRequest->branch_id,
            'direction'      => 'C',
            'source_type'    => 'pc_request',
            'source_id'      => $requestId,
            'amount'         => $pcRequest->requested_amount,
            'balance_before' => $balanceBefore,
            'balance_after'  => $balanceAfter,
            'narration'      => 'PC Request #' . $requestId . ' transferred. Ref: ' . $request->transfer_ref,
            'created_by'     => $loginUserId,
            'created_at'     => now(),
        ]);
    }

    if ($request->accounts_status === 'rejected') {
        $pcRequest->ticket_status = 'closed';
        $pcRequest->closed_at     = now();
        $issueTicketStatus        = 3;
    }
    if ($request->accounts_status === 'closed') {
        $pcRequest->ticket_status = 'closed';
        $pcRequest->closed_at     = now();
        $issueTicketStatus        = 2;
    }

    DB::connection('sqlsrv')
        ->table('issueTicket')
        ->where('ticketId', $pcRequest->ticket_id)
        ->update(['Status' => $issueTicketStatus]);

    $pcRequest->save();

    return response()->json([
        'status'  => true,
        'message' => 'Status updated successfully',
    ]);
}
}
