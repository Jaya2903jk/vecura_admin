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

class VsupportController extends Controller
{

    public function index(Request $request)
    {
        $complaints = CustomerRefundComplaint::with([
            'ticket',
            'category',
            'issue',
            'createdUser',
            'acceptedUser',
            'followups',
            'Customer.location'
        ])->orderBy('complaintid', 'desc')->get()->unique('CustomerCode');
        $currentUserId = session('user_id');

        // $assignList = UserMaster::where('UserStatus', 'Active')->get();
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
        return view('vsupport.index', compact('complaints', 'assignList'));
    }
    public function view($id)
    {
        $complaints = CustomerRefundComplaint::with([
            'customer.location',
            'createdUser',
            'acceptedUser'
        ])
            ->where('ReferenceNo', $id)
            ->orderBy('complaintid', 'desc')
            ->get();
        // dd($complaints);
          $customer = $complaints->first()?->customer;
        return view('vsupport.view', compact('complaints','customer'));
    }
     public function create($id)
    {
        $complaints = CustomerRefundComplaint::with([
            'customer.location',
            'createdUser',
            'acceptedUser'
        ])
            ->where('ReferenceNo', $id)
            ->orderBy('complaintid', 'desc')
            ->get();
        // dd($complaints);
          $customer = $complaints->first()?->customer;
        return view('vsupport.followup', compact('complaints','customer'));
    }

}
