<?php

namespace App\Http\Controllers;

use App\Models\HrManpowerRequest;
use App\Models\IssueTicket;
use App\Models\UserMaster;
use Illuminate\Http\Request;

class HrManpowerController extends Controller
{
    public function view($ticketId)
    {
        // Get ticket details
        $ticket = IssueTicket::with(['department', 'location', 'customer'])
            ->where('ticketId', $ticketId)
            ->first();

        // Get manpower data
        $manpower = HrManpowerRequest::where('ticketId', $ticketId)->first();

        if (! $manpower) {
            return redirect()->back()->with('error', 'Manpower request not found');
        }
        $workflowHistory = HrManpowerRequest::with([
            'department',
            'category',
            'escalation',
        ])
            ->where('ticketId', $ticketId)
            ->latest()
            ->get();
        // Logged in user
        $currentUserId = session('user_id');

        $loginUser = UserMaster::where('UserID', $currentUserId)->first();

        // Check permission for Self Assign button
        $canSelfAssign = false;

        if ($loginUser) {

            // Admin check
            if ($loginUser->Role === 'Admin') {
                $canSelfAssign = true;
            }

            // HR Department designation check
            if (in_array($loginUser->Designation, ['DES-0025', 'DES-0030'])) {
                $canSelfAssign = true;
            }
            if (
                $manpower->assigned_hr_id == $currentUserId
            ) {

                $canSelfAssign = true;
            }
        }
        $employees = UserMaster::whereIn('Designation', [
            'DES-0025',
            'DES-0030',
            'DES-0061',
            'DES-0135',
            'DES-0176',
        ])->get();

        $metaData = $manpower->meta_data ?? [];

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

                // FINAL FORMAT: Name - Designation
                // 'changedBy' => $name.' - '.$designation,
                'changedBy' => $name,

                'changedAt' => $item['date'] ?? null,
            ];
        });

        return view('ticket.view_manpower', compact('ticket', 'manpower', 'workflowHistory', 'canSelfAssign', 'employees', 'actionHistory'));
    }

    public function updateApproval(Request $request)
    {
        $request->validate([
            'request_id' => 'required',
            'status' => 'required|in:Approved,Rejected',
            'remarks' => 'required',
        ]);

        if (session('role_name') != 'Admin') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $data = HrManpowerRequest::findOrFail($request->request_id);

        $data->approvalStatus = $request->status;
        // $data->remarks = $request->remarks;
        $data->updated_at = now();
        $data->updated_by = session('user_id');

        $ticket = IssueTicket::where('ticketId', $data->ticketId)->first();

        if ($ticket) {

            if ($request->status == 'Approved') {

                $ticket->Status = 1; // approved

            } elseif ($request->status == 'Rejected') {

                $ticket->Status = 3; // rejected

            }

            $ticket->ModifiedBy = session('user_id');
            $ticket->ModifiedDate = now();

            $ticket->save();
        }

        /*
        |--------------------------------------------------------------------------
        | RECRUITMENT STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->status == 'Approved') {
            $data->recruitmentStatus = 'Waiting HR';
        } else {
            $data->recruitmentStatus = null;
        }
        $this->updateMetaData(
            $data,
            'approval',
            [
                'status' => $request->status,
                'remarks' => $request->remarks,
                'updated_by' => session('user_id'),
                'updated_at' => now()->toDateTimeString(),
            ],
            [
                'type' => 'Approval',
                'status' => $request->status,
                'remarks' => $request->remarks,
                'user_id' => session('user_id'),
                'date' => now()->toDateTimeString(),
            ]
        );
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Updated Successfully',
        ]);
    }

    public function selfAssign(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required',
        ]);

        $manpower = HrManpowerRequest::findOrFail($id);

        $manpower->assigned_hr_id = $request->employee_id;
        $manpower->recruitmentStatus = 'InProgress';
        // $manpower->updated_by = session('user_id');
        // $manpower->updated_at = now();

        $this->updateMetaData(
            $manpower,
            'assignment',
            [
                'assigned_hr_id' => $request->employee_id,
                'assigned_by' => session('user_id'),
                'remarks' => $request->remarks,
                'assigned_at' => now()->toDateTimeString(),
            ],
            [
                'type' => 'Assignment',
                'assigned_hr_id' => $request->employee_id,
                'remarks' => $request->remarks,
                'user_id' => session('user_id'),
                'date' => now()->toDateTimeString(),
            ]
        );

        $manpower->save();

        return response()->json([
            'status' => true,
            'message' => 'HR assigned successfully',
        ]);
    }

    public function candidateStore(Request $request, $id)
    {
        $request->validate([
            'employee_id' => $request->status == 'Joined'
                       ? 'required'
                       : 'nullable',
            'status' => 'required',
            'remarks' => 'required',
        ]);
        // FIND REQUEST
        $manpower = HrManpowerRequest::findOrFail($id);
        // UPDATE TABLE
        $manpower->employee_id = $request->employee_id;
        $manpower->remarks = $request->remarks;
        $manpower->recruitmentStatus = $request->status;

        if ($request->status == 'Joined') {
            // $manpower->approvalStatus = 'Closed';
            $manpower->onboardingStatus = 'Completed';
            // UPDATE TICKET Closed STATUS = 3
            IssueTicket::where('ticketId', $manpower->ticketId)
                ->update([
                    'status' => 3,
                ]);
        }
        $this->updateMetaData(
            $manpower,
            'candidate_status_history',
            [
                'employee_id' => $request->employee_id,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'created_by' => session('user_id'),
                'created_at' => now()->toDateTimeString(),
            ],
            [
                'type' => 'Candidate Status Update',
                'employee_id' => $request->employee_id,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'user_id' => session('user_id'),
                'date' => now()->toDateTimeString(),
            ]
        );
        $manpower->save();

        return response()->json([
            'status' => true,
            'message' => 'Candidate status updated successfully',

        ]);
    }

    private function updateMetaData($model, $metaKey, array $metaValues = [], array $historyValues = [])
    {
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
