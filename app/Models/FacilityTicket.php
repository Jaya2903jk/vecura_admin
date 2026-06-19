<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityTicket extends Model
{
    protected $table    = 'facility_ticket';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ticket_id',
        'branch_id',
        'department_id',
        'category_id',
        'issue_id',
        'facility_category_id',
        'request_type',
        'description',
        'status',
        'remarks',
        'reviewed_by',
        'reviewed_at',
        'meta_data',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'meta_data'   => 'array',
        'reviewed_at' => 'datetime',
    ];
    public function ticket()
    {
        return $this->belongsTo(IssueTicket::class, 'ticket_id', 'ticketId');
    }
    public function facilityCategory()
    {
        return $this->belongsTo(FacilityIssueCategory::class, 'facility_category_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'departmentId', 'Departmentid');
    }

    public function category()
    {
        return $this->belongsTo(IssueCategory::class, 'category_id', 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(UserMaster::class, 'created_by', 'UserID');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(UserMaster::class, 'reviewed_by', 'UserID');
    }

    public function requestTypeLabel(): string
    {
        return match ($this->request_type) {
            'new'         => 'New Request',
            'replacement' => 'Replacement',
            'service'     => 'Service Request',
            default       => ucfirst($this->request_type),
        };
    }
}
