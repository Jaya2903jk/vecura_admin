<?php

// ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===
// FILE: app/Http/Controllers/BiomedicalTicketController.php  ( excerpt )
// ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===

namespace App\Http\Controllers;

use App\Models\BiomedicalTicket;
use App\Models\IssueTicket;
use App\Models\MachineIssues;
use App\Models\UserMaster;
use Illuminate\Http\Request;

class BiomedicalController extends Controller
{
    const BIOMEDICAL_DEPARTMENT = 31;

    const NEW_REQUEST = 21;

    const REPLACEMENT_REQUEST = 22;
    // issueId

    const SERVICE_REQUEST = 23;
    // issueId

    public function view($ticketId)
    {
        $ticket = IssueTicket::with(['department', 'location', 'customer'])
            ->where('ticketId', $ticketId)
            ->first();

        if (! $ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        $biomedical = BiomedicalTicket::with([
            'category',    // IssueCategory  – categoryId
            'issue',       // IssueMaster    – issueId  ( escalation type )
            'machine',     // MachineMaster  – machineId
            'createdBy',   // UserMaster     – created_by
        ])
            ->where('ticketId', $ticketId)
            ->first();
        if (! $biomedical) {
            return redirect()->back()->with('error', 'Biomedical request not found.');
        }
        $machineIssues = collect();
        if ($biomedical->machineIssueIds) {
            $ids = explode(',', $biomedical->machineIssueIds);
            $machineIssues = MachineIssues::whereIn('machineIssueId', $ids)->get();
        }
        /* ── Determine escalation type ────────────────────────────── */
        $issueId = (int) $biomedical->issueId;
        $isNewRequest = ($issueId === self::NEW_REQUEST);
        $isReplacement = ($issueId === self::REPLACEMENT_REQUEST);
        $isService = ($issueId === self::SERVICE_REQUEST);

        $currentUserId = session('user_id');
        $roleName = session('role_name');
        $isAdmin = ($roleName === 'Admin');

        /* ── Can the logged-in user approve? ──────────────────────── */
        $canApprove = $isAdmin && ($biomedical->status === 'Pending');
        $metaData = $biomedical->meta_data ?? [];
        if (is_string($metaData)) {
            $metaData = json_decode($metaData, true);
        }
        $actionHistory = collect($metaData['history'] ?? [])->map(function ($item) {
            $employee = UserMaster::with('designation')
                ->where('UserID', $item['user_id'] ?? null)
                ->first();
            $name = $employee->FullName
                ?? $employee->UserName
                ?? 'Unknown';
            $designation = $employee->designation->Designation
                ?? '-';

            return (object) [
                'status' => $item['status'] ?? $item['type'] ?? '-',
                'remarks' => $item['remarks'] ?? '-',
                'changedBy' => $name,
                'changedAt' => $item['date'] ?? null,
            ];
        });

        return view('ticket.view_biomedical', compact(
            'ticket',
            'biomedical',
            'machineIssues',
            'actionHistory',
            'isNewRequest',
            'isReplacement',
            'isService',
            'canApprove',
        ));
    }

    public function updateStatus(Request $request, $biomedicalId)
    {
        $request->validate([
            'status' => 'required',
            'remarks' => 'required',
        ]);

        $biomedical = BiomedicalTicket::findOrFail($biomedicalId);
        $statusMap = [
            'InProgress' => 1,
            'Resolved' => 2,
            'Closed' => 3,
        ];

        $allowedStatuses = array_keys($statusMap);

        if (! in_array($request->status, $allowedStatuses)) {
            return response()->json([
                'message' => 'Invalid status',
            ], 422);
        }

        if ($request->status == 'Closed') {

            $isOwner = $biomedical->created_by == session('user_id');
            $isAdmin = session('role_name') == 'Admin';

            if (! $isOwner && ! $isAdmin) {
                return response()->json([
                    'message' => 'You are not allowed to close this ticket',
                ], 403);
            }

            if (strtolower(trim($biomedical->status)) !== 'resolved') {
                return response()->json([
                    'message' => 'Ticket must be resolved before closing',
                ], 422);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Biomedical Table
        |--------------------------------------------------------------------------
        */

        $biomedical->status = $request->status;

        $this->updateMetaData(
            $biomedical,
            'status_update',
            [
                'current_status' => $request->status,
                'remarks' => $request->remarks,
                'updated_by' => session('user_id'),
                'updated_at' => now()->toDateTimeString(),
            ],
            [
                'type' => 'Status Update',
                'status' => $request->status,
                'remarks' => $request->remarks,
                'user_id' => session('user_id'),
                'date' => now()->toDateTimeString(),
            ]
        );

        $biomedical->updated_by = session('user_id');
        $biomedical->save();

        /*
        |--------------------------------------------------------------------------
        | Update issueTicket Table
        |--------------------------------------------------------------------------
        | BiomedicalTickets.ticketId
        | issueTicket.ticketId
        */

        IssueTicket::where('ticketId', $biomedical->ticketId)
            ->update([
                'Status' => $statusMap[$request->status],
                'ModifiedBy' => session('user_id'),
                'ModifiedDate' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully',
        ]);
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
}
