<?php

namespace App\Http\Controllers;

use App\Models\FacilityTicket;
use App\Models\FacilityIssueCategory;
use App\Models\IssueTicket;
use App\Models\UserMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    // ── View ──────────────────────────────────────────────────────────────
    public function view($ticketId)
    {
        $loginUserId = session('user_id');
        $roleName    = session('role_name');
        $roleId      = session('role_id');

        // ── 1. Base issueTicket (SQL Server) ──────────────────────────────
        $ticket = IssueTicket::with(['department', 'location', 'customer'])
            ->where('ticketId', $ticketId)
            ->first();

        if (! $ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        // ── 2. Facility-specific record (MySQL) ───────────────────────────
        $facility = FacilityTicket::with([
            'facilityCategory',  // FacilityIssueCategory – facility_category_id
            'category',          // IssueCategory         – category_id
            'createdBy',         // UserMaster            – created_by
            'reviewedBy',        // UserMaster            – reviewed_by
        ])
            ->where('ticket_id', $ticketId)
            ->first();

        if (! $facility) {
            return redirect()->route('ticket.view', $ticketId);
        }

        // ── 3. Determine request type flags ───────────────────────────────
        $isNewRequest  = ($facility->request_type === 'new');
        $isReplacement = ($facility->request_type === 'replacement');
        $isService     = ($facility->request_type === 'service');

        // ── 4. Admin / approve check ──────────────────────────────────────
        $isAdmin    = in_array($roleId, [1, 2]) || $roleName === 'Admin';
        $canApprove = $isAdmin && $facility->status !== 'Closed';

        // ── 5. Build status history from meta_data ────────────────────────
        $metaData = $facility->meta_data ?? [];
        if (is_string($metaData)) {
            $metaData = json_decode($metaData, true) ?? [];
        }

        $statusHistory = collect($metaData['history'] ?? [])
            ->map(function ($item) {
                $employee = UserMaster::with('designation')
                    ->where('UserID', $item['changed_by'] ?? $item['user_id'] ?? null)
                    ->first();

                $name = $employee
                    ? ($employee->FullName ?? $employee->UserName ?? 'Unknown')
                    : ($item['changed_name'] ?? 'Unknown');

                return (object) [
                    'action'    => $item['action']  ?? $item['status'] ?? $item['type'] ?? '-',
                    'remarks'   => $item['remarks']  ?? '-',
                    'changedBy' => $name,
                    'changedAt' => $item['date']     ?? null,
                ];
            })
            ->reverse()
            ->values();

        return view('ticket.view_facility', compact(
            'ticket',
            'facility',
            'statusHistory',
            'isNewRequest',
            'isReplacement',
            'isService',
            'isAdmin',
            'canApprove',
        ));
    }

    // ── Update Status ─────────────────────────────────────────────────────
    public function updateStatus(Request $request, $facilityId)
    {
        $request->validate([
            'status'  => 'required',
            'remarks' => 'required|string|max:500',
        ]);

        $facility = FacilityTicket::findOrFail($facilityId);

        $statusMap = [
            'In Progress' => 1,
            'Resolved'    => 2,
            'Closed'      => 3,
            'Rejected'    => 4,
        ];

        if (! array_key_exists($request->status, $statusMap)) {
            return response()->json(['message' => 'Invalid status.'], 422);
        }

        if ($request->status === 'Closed') {
            $isOwner = $facility->created_by == session('user_id');
            $isAdmin = session('role_name') === 'Admin';

            if (! $isOwner && ! $isAdmin) {
                return response()->json([
                    'message' => 'You are not allowed to close this ticket.',
                ], 403);
            }

            if (strtolower(trim($facility->status)) !== 'resolved') {
                return response()->json([
                    'message' => 'Ticket must be resolved before closing.',
                ], 422);
            }
        }

        $this->updateMetaData(
            $facility,
            'status_update',
            [
                'current_status' => $request->status,
                'remarks'        => $request->remarks,
                'updated_by'     => session('user_id'),
                'updated_at'     => now()->toDateTimeString(),
            ],
            [
                'action'    => strtoupper($request->status),
                'remarks'   => $request->remarks,
                'user_id'   => session('user_id'),
                'date'      => now()->toDateTimeString(),
            ]
        );

        $facility->status     = $request->status;
        $facility->updated_by = session('user_id');

        if (in_array($request->status, ['Resolved', 'Rejected'])) {
            $facility->reviewed_by = session('user_id');
            $facility->reviewed_at = now();
            $facility->remarks     = $request->remarks;
        }

        $facility->save();

        IssueTicket::where('ticketId', $facility->ticket_id)
            ->update([
                'Status'       => $statusMap[$request->status],
                'ModifiedBy'   => session('user_id'),
                'ModifiedDate' => now(),
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    private function updateMetaData(
        $model,
        string $metaKey,
        array $metaValues = [],
        array $historyValues = []
    ) {
        $meta = $model->meta_data ?? [];
        if (is_string($meta)) {
            $meta = json_decode($meta, true) ?? [];
        }

        $oldMeta          = $meta[$metaKey] ?? [];
        $meta[$metaKey]   = array_merge($oldMeta, $metaValues);

        if (! isset($meta['history'])) {
            $meta['history'] = [];
        }
        $meta['history'][] = $historyValues;

        $model->meta_data = $meta;

        return $model;
    }

    // ── Get Facility Categories (AJAX) ────────────────────────────────────
    public function getCategories()
    {
        $categories = FacilityIssueCategory::where('status', 1)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }
}
