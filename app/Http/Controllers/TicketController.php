<?php

// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use App\Models\ComplaintFollowup;
use App\Models\CustomerRefundComplaint;
use App\Models\HrTicketDetail;
use App\Models\IssueCategory;
use App\Models\IssueMaster;
use App\Models\IssueTicket;
use App\Models\PatientPersonalDetail;
use App\Models\UserMaster;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $totalTickets = IssueTicket::where('type', 'complaint')->count();

        $perPage = $request->get('per_page', 10);
        $status = $request->status;
        $type = $request->type;
        $q = IssueTicket::with(['department', 'customer', 'location', 'complaints'])
            ->withCount([
                'complaints',
                'complaints as pending_count' => function ($query) {
                    $query->where('callStatus', 'pending');
                },
                'complaints as inprogress_count' => function ($query) {
                    $query->where('callStatus', 'InProgress');
                },
                'complaints as closed_count' => function ($query) {
                    $query->where('callStatus', 'Closed');
                },
            ])
            ->orderBy('CreatedDate', 'desc')
            ->whereIn('type', ['complaint', 'hr']);

        if ($request->has('type') && $type != '') {
            $q->where('type', $type);
        }
        if ($request->has('status')) {
            $q->where('Status', $status);
        }
        $tickets = $q->paginate($perPage)->appends($request->all());
        $currentUserId = session('user_id');

        $assignList = UserMaster::with('designation')
            ->where('UserID', '!=', $currentUserId)
            ->where('UserStatus', 'Active')
            ->whereIn('UserCode', [
                'HEC-1453',
                'HEC-1150',
                'USE-0305',
                'HEC-0901',
                'HEC-0270',
                'HEC-0210',
                'HEC-1197',
                'HEC-1430',
                'HEC-1463',
                'HEC-1222',
                'HEC-0729',
                'HEC-1280',
                'HEC-1329',
                'HEC-1003',
                'HEC-1378',
                'HEC-1379',
                'HEC-1259',
                'HEC-1443',
                'HEC-1330',
                'HEC-1117',
                'HEC-0982',
                'HEC-1202',
                'USE-0431',
                'HEC-1381',
                'HEC-1404',
            ])
            ->get()
            ->sortBy(function ($item) {
                return $item->designation->Designation ?? '';
            })
            ->values();
        // dd($tickets);
        $leaveRequestId = config('ticket.LEAVE_REQUEST');

        return view('ticket.index', compact('tickets', 'assignList', 'totalTickets', 'perPage', 'leaveRequestId'));
    }

    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validator = Validator::make($request->all(), [
    //         'Department'        => 'required',
    //         'Complaint'         => 'required',
    //         'TypeofEscalation'  => 'required',
    //         'customer_code'     => 'required',
    //         'customer_name'     => 'required',
    //         'assign_to'         => 'required',
    //         'source'            => 'required',
    //         'feedback'          => 'required',
    //         'alternateMobile'   => 'nullable|digits:10',

    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $user   = UserMaster::where('UserCode', $request->assign_to)->first();
    //     DB::beginTransaction();

    //     try {

    //         $userId = session('user_id');
    //         $user   = UserMaster::find($userId);

    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not found'
    //             ], 404);
    //         }

    //         $userCode = $user->UserCode;
    //         $patient  = PatientPersonalDetail::where('RegistrationNo', $request->customer_code)->first();
    //         $category = IssueCategory::find($request->Complaint);
    //         $issue    = IssueMaster::find($request->TypeofEscalation);

    //         $ticketId = DB::connection('sqlsrv')
    //             ->table('issueTicket')
    //             ->insertGetId([
    //                 'Department'   => $request->Department,
    //                 'Subject'      => $category->category_name ?? null,
    //                 'Issuelevel2'  => $category->category_name ?? null,
    //                 'Issuelevel3'  => $category->category_name ?? null,
    //                 'Issuelevel5'  => $category->category_name ?? null,
    //                 'CustomerCode' => $request->customer_code ?? null,
    //                 'CustomerName' => $request->customer_name ?? null,
    //                 'LocId'        => $user->Loc_id ?: ($patient->Loc_Id ?? 1),
    //                 'Branch'       => $user->Loc_id ?: ($patient->Loc_Id ?? 1),
    //                 'Status'       => 0,
    //                 'type'         => 'complaint',
    //                 'CreatedBy'    => $userCode,
    //                 'CreatedDate'  => now(),
    //                 'AcceptedBy'   => $userCode,
    //                 'RequiredTime' => 1,
    //                 'RequiredTimeType' => 'Day',

    //                 // Optional
    //                 'FromProduct'  => $request->from_product ?? '',
    //                 'ToProduct'    => $request->ToProduct ?? '',
    //                 'BankName'     => $request->bank_name ?? '',
    //                 'CardNo'       => $request->card_no ?? '',
    //                 'CashAmt'      => $request->cash_amt ?? 0,
    //                 'CardAmt'      => $request->card_amt ?? 0,
    //                 'ScheduledDate' => $request->scheduled_date ?? null,
    //                 'BillRaisedType' => $request->bill_raised_type ?? null,
    //                 'NewBillType'  => $request->new_bill_type ?? null,
    //                 'ProductCode'  => $request->product_code ?? null,
    //                 'ServiceCode'  => $request->service_code ?? null,
    //                 'ServiceName'  => $request->service_name ?? null,
    //                 'DiscountAmt'  => $request->discount_amt ?? 0,
    //                 'BillNoFrom'   => $request->bill_no_from ?? '',
    //                 'BillNoTo'     => $request->bill_no_to ?? '',
    //                 'NewRequestedBillDate' => $request->new_requested_bill_date ?? null,
    //                 'BillType'     => $request->bill_type ?? '',
    //                 'OriyanaId'    => $request->oriyana_id ?? '',
    //                 'MobileNo'     => $request->alternateMobile ?? '',
    //                 'EmpName'      => $user->UserName ?? '',
    //                 'ApprovedStatus' => 'Pending',
    //                 'ApprovedBy'   => '',
    //                 'Email'        => $user->Email ?? '',
    //             ]);

    //         $ticket = IssueTicket::find($ticketId);

    //         $existingRef = CustomerRefundComplaint::where('CustomerCode', $request->customer_code)
    //             ->value('ReferenceNo');

    //         if ($existingRef) {
    //             $ticketNo = $existingRef;
    //         } else {
    //             $last = CustomerRefundComplaint::orderBy('complaintid', 'desc')->first();
    //             $next = 1;

    //             if ($last && $last->ReferenceNo) {
    //                 preg_match('/TRY-(\d+)/', $last->ReferenceNo, $m);
    //                 $next = isset($m[1]) ? (int)$m[1] + 1 : 1;
    //             }

    //             $ticketNo = 'TRY-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    //         }

    //         $complaint = CustomerRefundComplaint::create([
    //             'ReferenceNo'        => $ticketNo,
    //             'CustomerCode'       => $request->customer_code,
    //             'CustomerName'       => $request->customer_name,
    //             'feedbackDate'       => now(),
    //             'feedback'           => $request->feedback,
    //             'CreatedBy'          => $userCode,
    //             'CreatedDate'        => now(),
    //             'ModifiedBy'         => $userCode,
    //             'ModifiedDate'       => now(),
    //             'alternateMobile'    => $request->alternateMobile,
    //             'sources'            => $request->source,
    //             'callAssignTo'       => $request->assign_to,
    //             'CurrentLevel'       => 1,
    //             'Complaint'          => $category->category_name ?? null,
    //             'TypeofEscalation'   => $issue->IssueName ?? null,
    //             'issue_master_id'    => $request->TypeofEscalation,
    //             'callStatus'         => 'Pending',
    //             'ticketId'           => $ticket->ticketId,
    //         ]);

    //         ComplaintFollowup::create([
    //             'complaint_id' => $complaint->complaintid,
    //             'ticket_id'    => $ticket->ticketId,
    //             'level'        => 1,
    //             'assigned_to'  => $request->assign_to,
    //             'action_by'    => $userId,
    //             'remarks'      => $request->feedback,
    //             'status'       => 'Pending',
    //             'created_at'   => now()
    //         ]);
    //         // $user->UserID
    //         DB::commit();
    //         NotificationService::send(
    //             23900,
    //             'New Ticket Created',
    //             'Ticket #' . $ticket->ticketId . ' created',
    //             'ticket',
    //             $ticket->ticketId
    //         );
    //         // NotificationService::send(
    //         //     $request->assign_to,
    //         //     'Ticket Assigned',
    //         //     'You have been assigned Ticket #' . $ticket->ticketId,
    //         //     'ticket',
    //         //     $ticket->ticketId
    //         // );

    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'Complaint Created Successfully',
    //             'data'    => $complaint,
    //             'ticket'  => $ticket
    //         ]);
    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'status'  => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $loginUserId = session('user_id');
            $loginUser = UserMaster::find($loginUserId);

            if (! $loginUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $userCode = $loginUser->UserCode;

            /*
        ======================================================
        LOCATION RESOLUTION
        ======================================================
        */
            $patient = null;

            if ($request->customer_code) {
                $patient = PatientPersonalDetail::where('RegistrationNo', $request->customer_code)->first();
            }

            if ($request->Department == 51) {
                $employee = UserMaster::where('UserID', $request->employee_id)->first();
                $locCode = $employee->Loc_id ?? $loginUser->Loc_id ?? 'GEN';
            } else {
                $locCode = $patient?->Loc_Id ?? $loginUser->Loc_id ?? 'GEN';
            }

            $month = date('ym');

            /*
        ======================================================
        HR FLOW
        ======================================================
        */
            if ($request->Department == 51) {

                $validator = Validator::make($request->all(), [
                    'Department' => 'required',
                    'Complaint' => 'required',
                    'TypeofEscalation' => 'required',
                    'feedback' => 'required',
                    'employee_id' => 'required',
                    // 'from_date' => 'required|date',
                    // 'to_date' => 'required|date',
                    'from_date' => 'required|date|after:today',
                    'to_date' => 'required|date|after:from_date',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $category = IssueCategory::find($request->Complaint);
                $issue = IssueMaster::find($request->TypeofEscalation);

                $prefix = $locCode . $month . 'T';
                $ticketNo = $this->generateTicketNo($prefix);

                $ticketId = DB::connection('sqlsrv')
                    ->table('issueTicket')
                    ->insertGetId([
                        'Department' => $request->Department,
                        'Subject' => $category->category_name ?? null,
                        'Issuelevel2' => $category->category_name ?? null,
                        'Issuelevel3' => $category->category_name ?? null,
                        'Issuelevel5' => $category->category_name ?? null,
                        // 'CustomerCode' => $request->customer_code ?? null,
                        // 'CustomerName' => $request->customer_name ?? null,
                        'CustomerCode' => $request->customer_code ?? 'HR',
                        'CustomerName' => $request->customer_name ?? 'HR Ticket',
                        'LocId' => $locCode,
                        'Branch' => $locCode,
                        'Status' => 0,
                        'type' => 'hr',
                        'CreatedBy' => $userCode,
                        'CreatedDate' => now(),
                        'AcceptedBy' => $userCode,
                        'RequiredTime' => 1,
                        'RequiredTimeType' => 'Day',
                        'FromProduct' => $request->from_product ?? '',
                        'ToProduct' => $request->ToProduct ?? '',
                        'BankName' => $request->bank_name ?? '',
                        'CardNo' => $request->card_no ?? '',
                        'CashAmt' => $request->cash_amt ?? 0,
                        'CardAmt' => $request->card_amt ?? 0,
                        'ScheduledDate' => $request->scheduled_date ?? null,
                        'BillRaisedType' => $request->bill_raised_type ?? null,
                        'NewBillType' => $request->new_bill_type ?? null,
                        'ProductCode' => $request->product_code ?? null,
                        'ServiceCode' => $request->service_code ?? null,
                        'ServiceName' => $request->service_name ?? null,
                        'DiscountAmt' => $request->discount_amt ?? 0,
                        'BillNoFrom' => $request->bill_no_from ?? '',
                        'BillNoTo' => $request->bill_no_to ?? '',
                        'NewRequestedBillDate' => $request->new_requested_bill_date ?? null,
                        'BillType' => $request->bill_type ?? '',
                        'OriyanaId' => $request->oriyana_id ?? '',
                        'MobileNo' => $request->alternateMobile ?? '',
                        'EmpName' => $loginUser->UserName ?? '',
                        'ApprovedStatus' => 'Pending',
                        'ApprovedBy' => '',
                        'Email' => $loginUser->Email ?? '',
                        'UserId' => $request->employee_id ?? '',

                    ]);

                HrTicketDetail::create([
                    'ticketId' => $ticketId,
                    'departmentId' => $request->Department,
                    'categoryId' => $request->Complaint,
                    'escalationTypeId' => $request->TypeofEscalation,
                    'employeeId' => $request->employee_id,
                    'fromDate' => $request->from_date,
                    'toDate' => $request->to_date,
                    'comments' => $request->feedback,
                    'status' => 'Pending',
                    'createdDate' => now(),
                    'created_by' => $loginUserId,

                ]);

                //  HR NOTIFICATION
                NotificationService::send(
                    23900,
                    'New HR Ticket Created',
                    'HR Ticket #' . $ticketId . ' created',
                    'ticket',
                    $ticketId
                );
            }

            /*
        ======================================================
        CUSTOMER FLOW
        ======================================================
        */ else {
                $validator = Validator::make($request->all(), [
                    'Department' => 'required',
                    'Complaint' => 'required',
                    'TypeofEscalation' => 'required',
                    'customer_code' => 'required',
                    'customer_name' => 'required',
                    'assign_to' => 'required',
                    'source' => 'required',
                    'feedback' => 'required',
                    'alternateMobile' => 'nullable|digits:10',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $category = IssueCategory::find($request->Complaint);
                $issue = IssueMaster::find($request->TypeofEscalation);

                $prefix = $locCode . $month . 'T';
                $ticketNo = $this->generateTicketNo($prefix);

                $ticketId = DB::connection('sqlsrv')
                    ->table('issueTicket')
                    ->insertGetId([
                        'Department' => $request->Department,
                        'Subject' => $category->category_name ?? null,
                        'Issuelevel2' => $category->category_name ?? null,
                        'Issuelevel3' => $category->category_name ?? null,
                        'Issuelevel5' => $category->category_name ?? null,
                        'CustomerCode' => $request->customer_code ?? null,
                        'CustomerName' => $request->customer_name ?? null,
                        'LocId' => $loginUser->Loc_id ?: ($patient?->Loc_Id ?? 1),
                        'Branch' => $loginUser->Loc_id ?: ($patient?->Loc_Id ?? 1),
                        'Status' => 0,
                        'type' => 'complaint',
                        'CreatedBy' => $userCode,
                        'CreatedDate' => now(),
                        'AcceptedBy' => $userCode,
                        'RequiredTime' => 1,
                        'RequiredTimeType' => 'Day',
                        'FromProduct' => $request->from_product ?? '',
                        'ToProduct' => $request->ToProduct ?? '',
                        'BankName' => $request->bank_name ?? '',
                        'CardNo' => $request->card_no ?? '',
                        'CashAmt' => $request->cash_amt ?? 0,
                        'CardAmt' => $request->card_amt ?? 0,
                        'ScheduledDate' => $request->scheduled_date ?? null,
                        'BillRaisedType' => $request->bill_raised_type ?? null,
                        'NewBillType' => $request->new_bill_type ?? null,
                        'ProductCode' => $request->product_code ?? null,
                        'ServiceCode' => $request->service_code ?? null,
                        'ServiceName' => $request->service_name ?? null,
                        'DiscountAmt' => $request->discount_amt ?? 0,
                        'BillNoFrom' => $request->bill_no_from ?? '',
                        'BillNoTo' => $request->bill_no_to ?? '',
                        'NewRequestedBillDate' => $request->new_requested_bill_date ?? null,
                        'BillType' => $request->bill_type ?? '',
                        'OriyanaId' => $request->oriyana_id ?? '',
                        'MobileNo' => $request->alternateMobile ?? '',
                        'EmpName' => $loginUser->UserName ?? '',
                        'ApprovedStatus' => 'Pending',
                        'ApprovedBy' => '',
                        'Email' => $loginUser->Email ?? '',
                    ]);

                $complaint = CustomerRefundComplaint::create([
                    'ReferenceNo' => $ticketNo,
                    'CustomerCode' => $request->customer_code,
                    'CustomerName' => $request->customer_name,
                    'feedback' => $request->feedback,
                    'CreatedBy' => $userCode,
                    'CreatedDate' => now(),
                    'callAssignTo' => $request->assign_to,
                    'sources' => $request->source,
                    'Complaint' => $category->category_name ?? null,
                    'TypeofEscalation' => $issue->IssueName ?? null,
                    'ticketId' => $ticketId,
                    'callStatus' => 'Pending',
                ]);

                ComplaintFollowup::create([
                    'complaint_id' => $complaint->complaintid,
                    'ticket_id' => $ticketId,
                    'level' => 1,
                    'assigned_to' => $request->assign_to,
                    'action_by' => $loginUserId,
                    'remarks' => $request->feedback,
                    'status' => 'Pending',
                    'created_at' => now(),
                ]);

                // CUSTOMER NOTIFICATION
                NotificationService::send(
                    23900,
                    'New Ticket Assigned',
                    'Ticket #' . $ticketId . ' assigned to you',
                    'ticket',
                    $ticketId
                );
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Ticket Created Successfully',
                'ticket_id' => $ticketId,
                'ticket_no' => $ticketNo,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateTicketNo($prefix)
    {
        $last = IssueTicket::where('TicketCode', 'LIKE', $prefix . '%')
            ->orderBy('ticketId', 'desc')
            ->first();

        $next = 1;

        if ($last && $last->TicketCode) {
            preg_match('/' . $prefix . '(\d+)/', $last->TicketCode, $m);
            $next = isset($m[1]) ? ((int) $m[1] + 1) : 1;
        }

        return $prefix . $next;
    }

    public function customerTickets(Request $request)
    {
        $category = IssueCategory::where('category_id', $request->category)->value('category_name');

        $tickets = IssueTicket::where('CustomerCode', $request->customer_code)
            ->where('Department', $request->department)
            ->where('Subject', $category)
            ->whereIn('Status', [0]) // 0 = Pending, 1 = InProgress
            ->orderBy('ticketId', 'desc')
            ->get(['ticketId', 'Subject', 'Status', 'CreatedDate']);

        return response()->json([
            'tickets' => $tickets,
        ]);
    }

    public function viewTicket($id)
    {
        // $ticket = IssueTicket::with(['complaints','hr'])->find($id);
        // dd(  $ticket->type);

        $ticket = IssueTicket::with([
            'department',
            'customer',
            'location',
            'complaints.createdUser',
            'complaints.acceptedUser',
            'complaints.issue',
            'complaints.followups',
            'complaints.followups.assignedUser',
            'complaints.followups.actionUser',
            'hr.employee',
            'hr.department',
            'hr.category',
            'hr.escalationType',
        ])
            // ->where('type', 'complaint')
            ->find($id);
        if (! $ticket) {
            abort(404);
        }
        $isAdmin = in_array(session('role_name'), ['Admin']);

        if ($ticket->type == 'hr') {
            $hr = $ticket->hr->first();

            if ($hr && $hr->status == 'Pending') {
                if ($isAdmin) {
                    $hr->status = 'InProgress';
                    $hr->updated_by = session('user_code');
                    $hr->updated_at = now();
                    $hr->save();

                    $ticket->status = 1;
                    $ticket->save();

                    // -------------------------
                    // HISTORY ENTRY (InProgress)
                    // -------------------------
                    $existingHistory = $hr->status_history
                        ? json_decode($hr->status_history, true)
                        : [];

                    $existingHistory[] = [
                        'status' => 'InProgress',
                        'comment' => 'Ticket moved to InProgress',
                        'updated_by' => session('user_code'),
                        'updated_at' => now()->toDateTimeString(),
                    ];

                    $hr->status_history = json_encode($existingHistory);
                    $hr->save();
                }
            }

            $statusHistory = [];
            $userIds = [];

            foreach ($ticket->hr as $hrItem) {
                if (!empty($hrItem->status_history)) {
                    $decoded = json_decode($hrItem->status_history, true);

                    if (is_array($decoded)) {
                        $statusHistory = array_merge($statusHistory, $decoded);

                        foreach ($decoded as $history) {
                            if (!empty($history['updated_by'])) {
                                $userIds[] = $history['updated_by'];
                            }
                        }
                    }
                }
            }
            $userIds = array_unique($userIds);
            $users = UserMaster::whereIn('UserID', $userIds)
                ->pluck('FullName', 'UserID'); // [UserID => FullName]

            // return view('ticket.view_hr', [
            //     'ticket' => $ticket,
            //     'hrData' => $hr,
            //     'statusHistory' => $statusHistory,
            //     'users' => $users,
            // ]);
            $currentUserId = session('user_id');

            $user = UserMaster::with('designation')
                ->where('UserID', $currentUserId)
                ->first();

            $designationName = strtolower($user->designation->Designation ?? '');

            $isAdmin = in_array(session('role_name'), ['Admin']);

            $isAuditTeam = in_array($designationName, [
                'audit executive',
                'audit manager',
                'audit',
                'hr',
                'hr manager'
            ]);

            // IMPORTANT: employee creator
            $isCreator = $currentUserId == ($hr->created_by ?? null);
            // pass today for date check
            $today = now();

            return view('ticket.view_hr', [
                'ticket' => $ticket,
                'hrData' => $hr,
                'statusHistory' => $statusHistory,
                'users' => $users,
                'isAdmin' => $isAdmin,
                'isAuditTeam' => $isAuditTeam,
                'isCreator' => $isCreator,
                'today' => $today
            ]);
        }
        $firstComplaint = $ticket->complaints()
            ->orderBy('CreatedDate', 'asc')
            ->first();
        //  dd($ticket->complaints->complaintid);
        if (! $ticket) {
            abort(404);
        }
        $latestComplaint = $ticket->complaints()
            ->orderBy('ModifiedDate', 'desc')
            ->first();
        $currentStatus = $latestComplaint->callStatus ?? 'Pending';
        $currentUserId = session('user_id');
        $assignList = UserMaster::with('designation')
            ->where('UserStatus', 'Active')
            ->where('UserID', '!=', $currentUserId)
            ->whereIn('UserCode', [
                'HEC-1453',
                'HEC-1150',
                'USE-0305',
                'HEC-0901',
                'HEC-0270',
                'HEC-0210',
                'HEC-1197',
                'HEC-1430',
                'HEC-1463',
                'HEC-1222',
                'HEC-0729',
                'HEC-1280',
                'HEC-1329',
                'HEC-1003',
                'HEC-1378',
                'HEC-1379',
                'HEC-1259',
                'HEC-1443',
                'HEC-1330',
                'HEC-1117',
                'HEC-0982',
                'HEC-1202',
                'USE-0431',
                'HEC-1381',
                'HEC-1404',
            ])
            ->get()
            ->sortBy(function ($item) {
                return $item->designation->Designation ?? '';
            })
            ->values();
        $pendingCount = $ticket->complaints->where('callStatus', 'Pending')->count();
        $inprogressCount = $ticket->complaints->where('callStatus', 'InProgress')->count();
        $closedCount = $ticket->complaints->where('callStatus', 'Closed')->count();

        $currentUserId = session('user_id');
        $user = UserMaster::with('designation')
            ->where('UserID', $currentUserId)
            ->first();

        $currentUserCode = $user->UserCode ?? null;
        $designationName = $user->designation->Designation ?? null;
        $currentUserCode = session('user_code'); // if available
        $currentUserCode = UserMaster::where('UserID', $currentUserId)
            ->value('UserCode');

        $firstComplaint = $ticket->complaints()
            ->orderBy('CreatedDate', 'asc')
            ->first();

        $latestComplaint = $ticket->complaints()
            ->orderBy('ModifiedDate', 'desc')
            ->first();

        $currentStatus = $latestComplaint->callStatus ?? 'Pending';

        // who is currently responsible for latest complaint
        $currentAssigneeId = $latestComplaint->callAssignTo ?? null;
        $createdById = $firstComplaint->CreatedBy ?? null;

        // ROLE FLAGS
        $isCreator = $currentUserId == $createdById;
        $isAssignee = $currentUserCode == $currentAssigneeId;

        $isAdmin = in_array(session('role_name'), ['Admin']);
        // $isAuditTeam = in_array(session('role_name'), ['Audit']);
        $isAuditTeam = in_array(strtolower($designationName), [
            'audit executive',
            'audit manager',
            'audit',
        ]);

        return view('ticket.view', compact(
            'ticket',
            'assignList',
            'pendingCount',
            'inprogressCount',
            'closedCount',
            'firstComplaint',
            'currentStatus',

            'isCreator',
            'isAssignee',
            'isAdmin',
            'isAuditTeam'
        ));
    }

    public function checkCustomerTicket(Request $request)
    {
        $customerCode = $request->customer_code;

        $ticket = CustomerRefundComplaint::where('CustomerCode', $customerCode)
            ->orderBy('CreatedDate', 'desc') //
            ->first();

        if ($ticket) {
            return response()->json([
                'exists' => true,
                'ticket_id' => $ticket->ticketId,
            ]);
        }

        return response()->json([
            'exists' => false,
        ]);
    }

    public function followup(Request $request)
    {
        if ($request->status == 'Resolved') {
            $request->validate([
                'complaint_id' => 'required',
                'ticket_id' => 'required',
                'status' => 'required',
            ]);
        } else {
            $request->validate([
                'complaint_id' => 'required',
                'ticket_id' => 'required',
                'assign_to' => 'required',
                'remarks' => 'required',
                'status' => 'required',
            ]);
        }

        DB::beginTransaction();

        try {

            $ticket = IssueTicket::where('ticketId', $request->ticket_id)->firstOrFail();

            $baseComplaint = CustomerRefundComplaint::where('complaintid', $request->complaint_id)
                ->where('ticketId', $request->ticket_id)
                ->firstOrFail();
            $userId = session('user_id');
            $user = UserMaster::findOrFail($userId);

            $assignTo = ($request->status == 'Resolved')
                ? $user->UserCode
                : $request->assign_to;

            CustomerRefundComplaint::where('ticketId', $request->ticket_id)
                ->update([
                    // 'callAssignTo' => $assignTo,
                    'callStatus' => $request->status,
                    'ModifiedDate' => now(),
                ]);

            CustomerRefundComplaint::create([
                'ReferenceNo' => $baseComplaint->ReferenceNo,
                'CustomerCode' => $baseComplaint->CustomerCode,
                'CustomerName' => $baseComplaint->CustomerName,
                'feedbackDate' => now(),

                'feedback' => $request->status == 'Resolved'
                    ? 'Resolved'
                    : $request->remarks,

                'CreatedBy' => $user->UserCode,
                'CreatedDate' => now(),
                'ModifiedBy' => $user->UserCode,
                'ModifiedDate' => now(),
                'alternateMobile' => $baseComplaint->alternateMobile,

                'callAssignTo' => $assignTo,
                'sources' => $baseComplaint->sources,
                'Complaint' => $baseComplaint->Complaint,
                'TypeofEscalation' => $baseComplaint->TypeofEscalation,
                'issue_master_id' => $baseComplaint->issue_master_id,

                'callStatus' => $request->status,

                'ticketId' => $baseComplaint->ticketId,
                'CurrentLevel' => 1,
            ]);

            $ticket->Status = ($request->status == 'Resolved') ? 2 : 1;
            $ticket->save();

            DB::commit();
            $assignedUser = UserMaster::where('UserCode', $assignTo)->first();

            // if ($assignedUser) {
            //     $assignedUser->notify(new ComplaintNotification([
            //         'message' => 'Complaint updated: ' . $request->status,
            //         'ticket_id' => $request->ticket_id
            //     ]));
            // }

            return response()->json([
                'status' => true,
                'message' => $request->status == 'Resolved'
                    ? 'Complaint Resolved Successfully'
                    : 'Follow-up Added Successfully',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function closeComplaint(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = UserMaster::findOrFail(session('user_id'));

            $complaints = CustomerRefundComplaint::where('ticketId', $request->ticket_id)->get();

            if ($complaints->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No complaints found for this ticket',
                ]);
            }

            // 1. Update ALL complaints to Closed
            CustomerRefundComplaint::where('ticketId', $request->ticket_id)
                ->update([
                    'callStatus' => 'Closed',
                    'ModifiedDate' => now(),
                ]);

            // 2. Create ONLY ONE history entry for the ticket
            $first = $complaints->first();

            CustomerRefundComplaint::create([
                'ReferenceNo' => $first->ReferenceNo,
                'CustomerCode' => $first->CustomerCode,
                'CustomerName' => $first->CustomerName,
                'feedbackDate' => now(),
                'feedback' => 'Ticket Closed',
                'CreatedBy' => $user->UserCode,
                'CreatedDate' => now(),
                'ModifiedBy' => $user->UserCode,
                'ModifiedDate' => now(),
                'alternateMobile' => $first->alternateMobile,
                'callAssignTo' => $user->UserCode,
                'sources' => $first->sources,
                'Complaint' => 'ALL COMPLAINTS CLOSED',
                'TypeofEscalation' => $first->TypeofEscalation,
                'issue_master_id' => $first->issue_master_id,
                'callStatus' => 'Closed',
                'ticketId' => $first->ticketId,
                'CurrentLevel' => 1,
            ]);

            // 3. Close ticket
            IssueTicket::where('ticketId', $request->ticket_id)
                ->update(['Status' => 3]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Ticket and all complaints closed with single history entry',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateHrStatus(Request $request)
    {
        $hr = HrTicketDetail::find($request->hr_id);
        if (! $hr) {
            return response()->json([
                'status' => false,
                'message' => 'HR record not found',
            ]);
        }

        $userId = session('user_id');
        $comment = $request->comments ?? null;

        // =========================
        // UPDATE HR STATUS
        // =========================
        $hr->status = $request->status;
        $hr->updated_by = $userId;
        $hr->updated_at = now();

        // =========================
        // STATUS HISTORY
        // =========================
        $historyEntry = [
            'status' => $request->status,
            'comment' => $comment,
            'updated_by' => $userId,
            'updated_at' => now()->toDateTimeString(),
        ];

        $existingHistory = $hr->status_history
            ? json_decode($hr->status_history, true)
            : [];

        $existingHistory[] = $historyEntry;

        $hr->status_history = json_encode($existingHistory);

        // =========================
        // SAVE HR
        // =========================
        $hr->save();

        // =========================
        // UPDATE PARENT TICKET STATUS
        // =========================
        $ticket = IssueTicket::find($hr->ticketId);

        if ($ticket) {

            if ($request->status == 'InProgress') {
                $ticket->Status = 1;
            }

            if ($request->status == 'Resolved') {
                $ticket->Status = 2;
            }

            if ($request->status == 'Closed') {
                $ticket->Status = 3;
            }

            $ticket->ModifiedBy = $userId;
            $ticket->ModifiedDate = now();

            $ticket->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Updated successfully',
        ]);
    }
}
