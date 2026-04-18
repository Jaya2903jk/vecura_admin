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

use Carbon\Carbon;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $q = IssueTicket::with(['department', 'customer', 'location', 'complaints'])
            ->withCount([
                'complaints as pending_count' => function ($query) {
                    $query->where('callStatus', 'pending');
                },
                'complaints as inprogress_count' => function ($query) {
                    $query->where('callStatus', 'InProgress');
                },
                'complaints as closed_count' => function ($query) {
                    $query->where('callStatus', 'Closed');
                },
            ]);

        // $tickets = $q->orderBy('ticketId', 'desc')
        //     ->paginate(10);
        $tickets = $q->where('type', 'complaint')
            ->orderBy('ticketId', 'desc')
            ->paginate(10);

        $assignList = UserMaster::with('designation')
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
        return view('ticket.index', compact('tickets', 'assignList'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Department' => 'required',
            'Complaint' => 'required',
            'TypeofEscalation' => 'required',
            'customer_code' => 'required',
            'assign_to' => 'required',
            'source' => 'required',
            'call_status' => 'required',
            'feedback' => 'required',
            'alternateMobile' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = session('user_id');
        DB::beginTransaction();

        try {

            $patient = PatientPersonalDetail::where('RegistrationNo', $request->customer_code)->first();
            $user = UserMaster::find($userId);
            // return response()->json($user);
            $department = IssueDepartment::find($request->Department);
            $category   = IssueCategory::find($request->Complaint);
            $issue      = IssueMaster::find($request->TypeofEscalation);

            $userCode = $user->UserCode;
            $ticket = IssueTicket::where('CustomerCode', $request->customer_code)
                // ->where('Status', 0) // pending/open tickets only
                ->first();
            if (!$ticket) {
                $ticketId = DB::connection('sqlsrv')
                    ->table('issueTicket')
                    ->insertGetId([
                        'Department'   => $request->Department,
                        'Subject'      => $category->category_name ?? null,
                        'Issuelevel2'  => $category->category_name ?? null,
                        'Issuelevel3'  => $category->category_name ?? null, //
                        'Issuelevel5'  => $category->category_name ?? null,
                        'CustomerCode' => $request->customer_code,
                        'CustomerName' => $request->customer_name,
                        'LocId' => $user->Loc_id ?: ($patient->Loc_Id ?: 1),
                        'Branch' => $user->Loc_id ?: ($patient->Loc_Id ?: 1),
                        'Status'       => 0,
                        'type'         => 'complaint',
                        'CreatedBy'    => $userCode,
                        'CreatedDate'  => now(),
                        'AcceptedBy'   => $userCode,
                        'RequiredTime' => 1,
                        'RequiredTimeType' => 'Day',
                        'AttachFile'   => '',
                        'FromProduct'  => $request->from_product ?? '',
                        'ToProduct'  => $request->ToProduct ?? '',
                        'BankName'  => $request->bank_name ?? '',
                        'CardNo'  => $request->card_no ?? '',
                        'CashAmt'  => $request->cash_amt ?? 0,
                        'CardAmt'  => $request->card_amt ?? 0,
                        'ScheduledDate'  => $request->scheduled_date ?? null,
                        'BillRaisedType'  => $request->bill_raised_type ?? null,
                        'NewBillType'  => $request->new_bill_type ?? null,
                        'ProductCode'  => $request->product_code ?? null,
                        'ServiceCode'  => $request->service_code ?? null,
                        'ServiceName'  => $request->service_name ?? null,
                        'DiscountAmt'  => $request->discount_amt ?? 0,
                        'BillNoFrom'  => $request->bill_no_from ?? '',
                        'BillNoTo'  => $request->bill_no_to ?? '',
                        'NewRequestedBillDate'  => $request->new_requested_bill_date ?? null,
                        'BillType'  => $request->bill_type ?? '',
                        'OriyanaId'  => $request->oriyana_id ?? '',
                        'MobileNo'  => $request->alternate_mobile ?? '',
                        'EmpName'  => $user->UserName ?? '',
                        'ApprovedStatus' => 'Pending',
                        'ApprovedBy' => '',
                        'Email' => $user->Email ?? '',

                    ]);
                $ticket = IssueTicket::find($ticketId);
            }
            $last = CustomerRefundComplaint::orderBy('complaintid', 'desc')->first();
            $next = 1;

            if ($last && $last->ReferenceNo) {
                preg_match('/TRY-(\d+)/', $last->ReferenceNo, $m);
                $next = isset($m[1]) ? (int)$m[1] + 1 : 1;
            }
            $ticketNo = 'TRY-' . str_pad($next, 4, '0', STR_PAD_LEFT);


            $complaint = CustomerRefundComplaint::create([
                'ReferenceNo' => $ticketNo,
                'CustomerCode' => $request->customer_code,
                'CustomerName' => $request->customer_name,
                'feedbackDate' => now(),
                'feedback' => $request->feedback,
                'CreatedBy' =>  $userCode,
                'CreatedDate' => now(),
                'ModifiedBy' =>  $userCode,
                'ModifiedDate' => now(),
                'alternateMobile' => $request->alternateMobile,
                'sources' => $request->source,
                'callAssignTo' => $request->assign_to,
                // 'DepartmentName' => $department->DepartmentName ?? null,
                'Complaint' => $category->category_name ?? null,
                'TypeofEscalation' => $issue->IssueName ?? null,
                'issue_master_id' => $request->TypeofEscalation,
                'callStatus' => $request->call_status,
                'ticketId'   => $ticket->ticketId,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Complaint Created',
                'data' => $complaint,
                'ticket'  => $ticket,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Request $request, $id)
    {
        $ticket = IssueTicket::with([
            'department',
            'location',
            'customer'
        ])->find($id);

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
        $complaints = CustomerRefundComplaint::with([
            'category',
            'issue',
            'createdUser',
            'acceptedUser'
        ])
            ->where('ticketId', $id)
            ->orderBy('complaintid', 'desc')
            ->paginate(5);
        $pendingCount = CustomerRefundComplaint::where('ticketId', $id)
            ->where('callStatus', 'Pending')->count();

        $inprogressCount = CustomerRefundComplaint::where('ticketId', $id)
            ->where('callStatus', 'InProgress')->count();

        $closedCount = CustomerRefundComplaint::where('ticketId', $id)
            ->where('callStatus', 'Closed')->count();

        return response()->json([
            'status' => true,
            'data' => [
                'ticketId' => $ticket->ticketId,
                 'CustomerName' => $ticket->CustomerName,
        'CustomerCode' => $ticket->CustomerCode,
        'Branch' => $ticket->location->LocationName ?? $ticket->Branch,
        'mobile' => $ticket->customer->Mobile ?? '',

                'counts' => [
                    'pending' => $pendingCount,
                    'inprogress' => $inprogressCount,
                    'closed' => $closedCount,
                ],

                'complaints' => collect($complaints->items())->map(function ($c) {
                    return [
                        'complaintId' => $c->complaintid,
                        'Category' => $c->category->category_name ?? $c->Complaint,
                        'Issue' => $c->issue->IssueName ?? $c->TypeofEscalation,
                        'Comment' => $c->feedback ?? '',
                        'CreatedBy' => $c->createdUser->FullName ?? '',
                        'AssignedTo' => $c->acceptedUser->FullName ?? '',
                        'CreatedDate' => $c->CreatedDate
                            ? \Carbon\Carbon::parse($c->CreatedDate)->format('d-M-Y')
                            : null,
                        'Status' => $c->callStatus,
                        'Source' => $c->sources ?? '-',
                    ];
                }),

                'pagination' => [
                    'current_page' => $complaints->currentPage(),
                    'last_page' => $complaints->lastPage(),
                ]
            ]
        ]);
    }
    public function updateStatus(Request $request)
    {
        $complaint = CustomerRefundComplaint::find($request->complaintId);

        if (!$complaint) {
            return response()->json([
                'status' => false,
                'message' => 'Complaint not found'
            ]);
        }

        $complaint->callStatus = $request->status;
        $complaint->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully',
            'ticketId' => $complaint->ticketId //
        ]);
    }
}
