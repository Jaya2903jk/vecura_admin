<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrTicketDetail extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'hr_ticket_details';

    protected $primaryKey = 'hrTicketId';

    public $timestamps = false;

    protected $fillable = [
        'ticketId',
        'departmentId',
        'categoryId',
        'escalationTypeId',
        'employeeId',
        'employeeName',
        'fromDate',
        'toDate',
        'attendanceDate',
        'comments',
        'attachmentPath',
        'originalFileName',
        'status',
        'createdDate',
        'created_by',
        'updated_by',
        'updated_at',
        'status_history',
    ];

    public function ticket()
    {
        return $this->belongsTo(IssueTicket::class, 'ticketId', 'ticketId');
    }

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'departmentId', 'DepartmentId');
    }

    public function category()
    {
        return $this->belongsTo(IssueCategory::class, 'categoryId', 'category_id');
    }

    public function escalationType()
    {
        return $this->belongsTo(IssueMaster::class, 'escalationTypeId', 'IssueId');
    }

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'employeeId', 'UserID');
    }
}
