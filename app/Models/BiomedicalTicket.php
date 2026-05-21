<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiomedicalTicket extends Model
{
    protected $table = 'BiomedicalTickets';

    protected $fillable = [

        'ticketId',
        'departmentId',
        'categoryId',
        'issueId',
        'machineId',
        'machineIssueType',
        'comments',
        'status',
        'meta_data',
        'created_by',
        'updated_by',
        'machineIssueIds',
    ];

    protected $casts = [

        'meta_data' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(IssueTicket::class, 'ticketId', 'ticketId');
    }

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'departmentId', 'Departmentid');
    }

    public function category()
    {
        return $this->belongsTo(IssueCategory::class, 'categoryId', 'category_id');
    }

    public function issue()
    {
        return $this->belongsTo(IssueMaster::class, 'issueId', 'IssueId');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machineId', 'MachineId');
    }
    //  public function machine()
    // {
    //     return $this->belongsTo(MachineIssues::class, 'machineId', 'machineIssueId');
    // }

    public function createdBy()
    {
        return $this->belongsTo(UserMaster::class, 'created_by', 'UserID');
    }

    /** Who last updated */
    public function updatedBy()
    {
        return $this->belongsTo(UserMaster::class, 'updated_by', 'UserID');
    }

    public function escalationLabel(): string
    {
        return match ((int) $this->issueId) {
            21 => 'New Request',
            22 => 'Replacement Request',
            23 => 'Service Request',
            default => $this->issue->EscalationName ?? '—',
        };
    }

    /** Status badge CSS class */
    public function statusClass(): string
    {
        return match ($this->status) {
            'Approved' => 'td-pill-success',
            'Rejected' => 'td-pill-danger',
            default => 'td-pill-warning',
        };
    }
}
