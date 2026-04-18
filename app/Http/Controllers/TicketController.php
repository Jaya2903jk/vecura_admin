<?php
// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerRefundComplaint;
use App\Models\IssueDepartment;
use App\Models\IssueCategory;
use App\Models\IssueMaster;
use App\Models\PatientPersonalDetail;
use App\Models\UserMaster;
use App\Models\IssueTicket;
use App\Models\ApprovalFlow;
use App\Models\ComplaintActionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ComplaintFollowup;

use Carbon\Carbon;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $totalTickets = IssueTicket::where('type', 'complaint')->count();

        // $q = IssueTicket::with(['department', 'customer', 'location', 'complaints'])
        //     ->withCount([
        //         'complaints',
        //         'complaints as pending_count' => function ($query) {
        //             $query->where('callStatus', 'pending');
        //         },
        //         'complaints as inprogress_count' => function ($query) {
        //             $query->where('callStatus', 'InProgress');
        //         },
        //         'complaints as closed_count' => function ($query) {
        //             $query->where('callStatus', 'Closed');
        //         },
        //     ])
        //     ;

        // $tickets = $q
        //     ->where('type', 'complaint')
        //     ->orderBy('ticketId', 'desc')
        //     ->paginate(10);
        $status     = $request->status;
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
            ->where('type', 'complaint');

        //  Status filter (from tabs)
        if ($status !== null && $status !== '') {
            $q->where('Status', $status);
        }
        $tickets = $q->paginate(10)->appends($request->all());
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
                'HEC-1404'
            ])
            ->get()
            ->sortBy(function ($item) {
                return $item->designation->Designation ?? '';
            })
            ->values();
            // dd($tickets);
        return view('ticket.index', compact('tickets', 'assignList', 'totalTickets'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Department'        => 'required',
            'Complaint'         => 'required',
            'TypeofEscalation'  => 'required',
            'customer_code'     => 'required',
            'customer_name'     => 'required',
            'assign_to'         => 'required',
            'source'            => 'required',
            'feedback'          => 'required',
            'alternateMobile'   => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $userId = session('user_id');
            $user   = UserMaster::find($userId);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $userCode = $user->UserCode;
            $patient  = PatientPersonalDetail::where('RegistrationNo', $request->customer_code)->first();
            $category = IssueCategory::find($request->Complaint);
            $issue    = IssueMaster::find($request->TypeofEscalation);


            $ticketId = DB::connection('sqlsrv')
                ->table('issueTicket')
                ->insertGetId([
                    'Department'   => $request->Department,
                    'Subject'      => $category->category_name ?? null,
                    'Issuelevel2'  => $category->category_name ?? null,
                    'Issuelevel3'  => $category->category_name ?? null,
                    'Issuelevel5'  => $category->category_name ?? null,
                    'CustomerCode' => $request->customer_code,
                    'CustomerName' => $request->customer_name,
                    'LocId'        => $user->Loc_id ?: ($patient->Loc_Id ?? 1),
                    'Branch'       => $user->Loc_id ?: ($patient->Loc_Id ?? 1),
                    'Status'       => 0,
                    'type'         => 'complaint',
                    'CreatedBy'    => $userCode,
                    'CreatedDate'  => now(),
                    'AcceptedBy'   => $userCode,
                    'RequiredTime' => 1,
                    'RequiredTimeType' => 'Day',

                    // Optional
                    'FromProduct'  => $request->from_product ?? '',
                    'ToProduct'    => $request->ToProduct ?? '',
                    'BankName'     => $request->bank_name ?? '',
                    'CardNo'       => $request->card_no ?? '',
                    'CashAmt'      => $request->cash_amt ?? 0,
                    'CardAmt'      => $request->card_amt ?? 0,
                    'ScheduledDate' => $request->scheduled_date ?? null,
                    'BillRaisedType' => $request->bill_raised_type ?? null,
                    'NewBillType'  => $request->new_bill_type ?? null,
                    'ProductCode'  => $request->product_code ?? null,
                    'ServiceCode'  => $request->service_code ?? null,
                    'ServiceName'  => $request->service_name ?? null,
                    'DiscountAmt'  => $request->discount_amt ?? 0,
                    'BillNoFrom'   => $request->bill_no_from ?? '',
                    'BillNoTo'     => $request->bill_no_to ?? '',
                    'NewRequestedBillDate' => $request->new_requested_bill_date ?? null,
                    'BillType'     => $request->bill_type ?? '',
                    'OriyanaId'    => $request->oriyana_id ?? '',
                    'MobileNo'     => $request->alternateMobile ?? '',
                    'EmpName'      => $user->UserName ?? '',
                    'ApprovedStatus' => 'Pending',
                    'ApprovedBy'   => '',
                    'Email'        => $user->Email ?? '',
                ]);

            $ticket = IssueTicket::find($ticketId);

            $existingRef = CustomerRefundComplaint::where('CustomerCode', $request->customer_code)
                ->value('ReferenceNo');

            if ($existingRef) {
                $ticketNo = $existingRef;
            } else {
                $last = CustomerRefundComplaint::orderBy('complaintid', 'desc')->first();
                $next = 1;

                if ($last && $last->ReferenceNo) {
                    preg_match('/TRY-(\d+)/', $last->ReferenceNo, $m);
                    $next = isset($m[1]) ? (int)$m[1] + 1 : 1;
                }

                $ticketNo = 'TRY-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }


            $complaint = CustomerRefundComplaint::create([
                'ReferenceNo'        => $ticketNo,
                'CustomerCode'       => $request->customer_code,
                'CustomerName'       => $request->customer_name,
                'feedbackDate'       => now(),
                'feedback'           => $request->feedback,
                'CreatedBy'          => $userCode,
                'CreatedDate'        => now(),
                'ModifiedBy'         => $userCode,
                'ModifiedDate'       => now(),
                'alternateMobile'    => $request->alternateMobile,
                'sources'            => $request->source,
                'callAssignTo'       => $request->assign_to,
                'CurrentLevel'       => 1,
                'Complaint'          => $category->category_name ?? null,
                'TypeofEscalation'   => $issue->IssueName ?? null,
                'issue_master_id'    => $request->TypeofEscalation,
                'callStatus'         => 'Pending',
                'ticketId'           => $ticket->ticketId,
            ]);


            ComplaintFollowup::create([
                'complaint_id' => $complaint->complaintid,
                'ticket_id'    => $ticket->ticketId,
                'level'        => 1,
                'assigned_to'  => $request->assign_to,
                'action_by'    => $userId,
                'remarks'      => $request->feedback,
                'status'       => 'Pending',
                'created_at'   => now()
            ]);

            DB::commit();


            return response()->json([
                'status'  => true,
                'message' => 'Complaint Created Successfully',
                'data'    => $complaint,
                'ticket'  => $ticket
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function customerTickets(Request $request)
    {
        $tickets = IssueTicket::where('CustomerCode', $request->customer_code)
            ->orderBy('ticketId', 'desc')
            ->get(['ticketId', 'Subject', 'Status', 'CreatedDate']);

        return response()->json([
            'tickets' => $tickets
        ]);
    }
    public function viewTicket($id)
    {
        $ticket = IssueTicket::with([
            'department',
            'customer',
            'location',
            'complaints.createdUser',
            'complaints.acceptedUser',
            'complaints.issue',
            'complaints.followups',
            'complaints.followups.assignedUser',
            'complaints.followups.actionUser'
        ])->where('type', 'complaint')->find($id);
        $firstComplaint = $ticket->complaints()
            ->orderBy('CreatedDate', 'asc')
            ->first();
        //  dd($ticket->complaints->complaintid);
        if (!$ticket) {
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
                'HEC-1404'
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
            'audit'
        ]);
        // dd($isAuditTeam,  $designationName);
        // dd(session('role_name'));
        // dd([
        //     'currentUserId'      => $currentUserId,
        //     'currentUserCode'    => $currentUserCode,

        //     'createdById'        => $createdById,
        //     'currentAssigneeId'  => $currentAssigneeId,

        //     'isCreator'          => $isCreator,
        //     'isAssignee'         => $isAssignee,

        //     'role_name'          => session('role_name'),
        //     'isAdmin'            => $isAdmin,
        //     'isAuditTeam'        => $isAuditTeam,

        //     'currentStatus'      => $currentStatus,

        //     'firstComplaint'     => $firstComplaint,
        //     'latestComplaint'    => $latestComplaint,
        // ]);
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
                'ticket_id' => $ticket->ticketId
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }
    public function followup(Request $request)
    {
        if ($request->status == 'Resolved') {
            $request->validate([
                'complaint_id' => 'required',
                'ticket_id'    => 'required',
                'status'       => 'required',
            ]);
        } else {
            $request->validate([
                'complaint_id' => 'required',
                'ticket_id'    => 'required',
                'assign_to'    => 'required',
                'remarks'      => 'required',
                'status'       => 'required',
            ]);
        }

        DB::beginTransaction();

        try {

            $ticket = IssueTicket::where('ticketId', $request->ticket_id)->firstOrFail();

            $baseComplaint = CustomerRefundComplaint::where('complaintid', $request->complaint_id)
                ->where('ticketId', $request->ticket_id)
                ->firstOrFail();
            $userId = session('user_id');
            $user   = UserMaster::findOrFail($userId);

            $assignTo = ($request->status == 'Resolved')
                ? $user->UserCode
                : $request->assign_to;


            CustomerRefundComplaint::where('ticketId', $request->ticket_id)
                ->update([
                    // 'callAssignTo' => $assignTo,
                    'callStatus'   => $request->status,
                    'ModifiedDate' => now()
                ]);

            CustomerRefundComplaint::create([
                'ReferenceNo'       => $baseComplaint->ReferenceNo,
                'CustomerCode'      => $baseComplaint->CustomerCode,
                'CustomerName'      => $baseComplaint->CustomerName,
                'feedbackDate'      => now(),

                'feedback'          => $request->status == 'Resolved'
                    ? 'Resolved'
                    : $request->remarks,

                'CreatedBy'         => $user->UserCode,
                'CreatedDate'       => now(),
                'ModifiedBy'        => $user->UserCode,
                'ModifiedDate'      => now(),
                'alternateMobile'   => $baseComplaint->alternateMobile,

                'callAssignTo'      => $assignTo,
                'sources'           => $baseComplaint->sources,
                'Complaint'         => $baseComplaint->Complaint,
                'TypeofEscalation'  => $baseComplaint->TypeofEscalation,
                'issue_master_id'   => $baseComplaint->issue_master_id,

                'callStatus'        => $request->status,

                'ticketId'          => $baseComplaint->ticketId,
                'CurrentLevel'      => 1,
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
                    : 'Follow-up Added Successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
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
                    'message' => 'No complaints found for this ticket'
                ]);
            }

            // 1. Update ALL complaints to Closed
            CustomerRefundComplaint::where('ticketId', $request->ticket_id)
                ->update([
                    'callStatus'   => 'Closed',
                    'ModifiedDate' => now(),
                ]);

            // 2. Create ONLY ONE history entry for the ticket
            $first = $complaints->first();

            CustomerRefundComplaint::create([
                'ReferenceNo'       => $first->ReferenceNo,
                'CustomerCode'      => $first->CustomerCode,
                'CustomerName'      => $first->CustomerName,
                'feedbackDate'      => now(),
                'feedback'          => 'Ticket Closed',
                'CreatedBy'         => $user->UserCode,
                'CreatedDate'       => now(),
                'ModifiedBy'        => $user->UserCode,
                'ModifiedDate'      => now(),
                'alternateMobile'   => $first->alternateMobile,
                'callAssignTo'      => $user->UserCode,
                'sources'           => $first->sources,
                'Complaint'         => 'ALL COMPLAINTS CLOSED',
                'TypeofEscalation'  => $first->TypeofEscalation,
                'issue_master_id'   => $first->issue_master_id,
                'callStatus'        => 'Closed',
                'ticketId'          => $first->ticketId,
                'CurrentLevel'      => 1,
            ]);

            // 3. Close ticket
            IssueTicket::where('ticketId', $request->ticket_id)
                ->update(['Status' => 3]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Ticket and all complaints closed with single history entry'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // public function followup(Request $request)
    // {
    //     // dd($request->all());
    //     if ($request->status == 'Resolved') {
    //         $request->validate([
    //             'complaint_id' => 'required',
    //             'ticket_id'    => 'required',
    //             'status'       => 'required',
    //         ]);
    //     } else {
    //         $request->validate([
    //             'complaint_id' => 'required',
    //             'ticket_id'    => 'required',
    //             'assign_to'    => 'required',
    //             'remarks'      => 'required',
    //             'status'       => 'required',
    //         ]);
    //     }

    //     DB::beginTransaction();

    //     try {

    //         $ticket = IssueTicket::where('ticketId', $request->ticket_id)->firstOrFail();

    //         // $oldComplaint = CustomerRefundComplaint::
    //         // // where('complaintid', $request->complaint_id)
    //         //     where('ticketId', $request->ticket_id)
    //         //     ->firstOrFail();

    //         $userId = session('user_id');
    //         $user   = UserMaster::find($userId);

    //         if ($request->status == 'Resolved') {
    //             $oldComplaint->callAssignTo = $user->UserCode;
    //         } else {
    //             $oldComplaint->callAssignTo = $request->assign_to;
    //         }
    //     //   dd($request->status);
    //         // $oldComplaint->callStatus   = $request->status == 'Resolved' ? 'Closed' : 'InProgress';
    //        $oldComplaint->callStatus   = $request->status;
    //         $oldComplaint->ModifiedDate = now();
    //         $oldComplaint->save();

    //         CustomerRefundComplaint::create([
    //             'ReferenceNo'       => $oldComplaint->ReferenceNo,
    //             'CustomerCode'      => $oldComplaint->CustomerCode,
    //             'CustomerName'      => $oldComplaint->CustomerName,
    //             'feedbackDate'      => now(),
    //             'feedback'          => $request->remarks ?? 'Resolved',
    //             'CreatedBy'         => $user->UserCode,
    //             'CreatedDate'       => now(),
    //             'ModifiedBy'        => $user->UserCode,
    //             'ModifiedDate'      => now(),
    //             'alternateMobile'   => $oldComplaint->alternateMobile,

    //             'callAssignTo'      => $request->status == 'Resolved'
    //                 ? $user->UserCode
    //                 : $request->assign_to,

    //             'sources'           => $oldComplaint->sources,
    //             'Complaint'         => $oldComplaint->Complaint,
    //             'TypeofEscalation'  => $oldComplaint->TypeofEscalation,
    //             'issue_master_id'   => $oldComplaint->issue_master_id,
    //             // 'callStatus'        => $request->status == 'Resolved' ? 'Closed' : 'InProgress',
    //               'callStatus' => $request->status,
    //             'ticketId'          => $oldComplaint->ticketId,
    //             'CurrentLevel'      => 1,
    //         ]);

    //         $ticket->Status = $request->status == 'Resolved' ? 2 : 1;
    //         $ticket->save();

    //         DB::commit();

    //         return response()->json([
    //             'status' => true,
    //             'message' => $request->status == 'Resolved'
    //                 ? 'Resolved '
    //                 : 'Follow-up '
    //         ]);
    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
}
