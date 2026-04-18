<?php

namespace App\Models;

use App\Models\ApprovalFlow;
use App\Models\ComplaintFollowup;
use App\Models\IssueCategory;
use App\Models\IssueMaster;
use App\Models\IssueTicket;
use App\Models\PatientPersonalDetail;
use App\Models\UserMaster;
use Illuminate\Database\Eloquent\Model;

class CustomerRefundComplaint extends Model
{
    protected $table = 'CustomerRefundComplaint';
    protected $primaryKey = 'complaintid';
    public $timestamps = false;
    // Since you have CreatedDate and ModifiedDate manually
    protected $attributes = [
        'CurrentLevel' => 0,
    ];
    protected $fillable = [
        'ReferenceNo',
        'CustomerCode',
        'CustomerName',
        'feedbackDate',
        'feedback',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate',
        'alternateMobile',
        'callAssignTo',
        'sources',
        'Complaint',
        'callStatus',
        'followupStatus',
        'CorporateConsultant',
        'ConsultantRemarks',
        'TypeofEscalation',
        'ticketId',
        'issue_master_id',
        'CurrentLevel' // <-- add this

    ];

    public function Customer()
    {
        return $this->belongsTo(PatientPersonalDetail::class, 'CustomerCode', 'RegistrationNo');
    }
    public function ticket()
    {
        return $this->belongsTo(IssueTicket::class, 'ticketId', 'ticketId');
    }

    public function category()
    {
        return $this->belongsTo(IssueCategory::class, 'Complaint', 'category_id');
    }

    public function issue()
    {
        return $this->belongsTo(IssueMaster::class, 'TypeofEscalation', 'IssueId');
    }

    public function createdUser()
    {
        return $this->belongsTo(UserMaster::class, 'CreatedBy', 'UserCode');
    }

    public function acceptedUser()
    {
        return $this->belongsTo(UserMaster::class, 'callAssignTo', 'UserCode');
    }

    public function approvalFlows()
    {
        return $this->hasMany(ApprovalFlow::class, 'issueId', 'issue_master_id')
            ->orderBy('levelOrder');
    }
    public function followups()
    {
        return $this->hasMany(ComplaintFollowup::class, 'complaint_id', 'complaintid')->orderBy('level', 'desc');;
    }
}
