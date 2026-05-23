<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IouRequest extends Model
{
    use HasFactory;

    protected $table = 'iou_requests';

    protected $primaryKey = 'iou_id';

    public $timestamps = false;

    protected $casts = [
        'meta_data' => 'array',
    ];

    protected $fillable = [

        'ticket_id',
        'Department',
        'Category',
        'TypeOfEscalation',
        'employee_id',
        'branch_id',
        'request_date',
        'requested_amount',
        'approved_amount',
        'paid_amount',
        'settlement_amount',
        'pending_balance',
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
        'settlement_date',
        'remarks',
        'created_at',
        'updated_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(IssueTicket::class, 'ticket_id', 'ticketId');
    }

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'Department', 'Departmentid');
    }

    public function category()
    {
        return $this->belongsTo(IssueCategory::class, 'Category', 'category_id');
    }

    public function issue()
    {
        return $this->belongsTo(IssueMaster::class, 'TypeOfEscalation', 'IssueId');
    }

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'employee_id', 'UserID');
    }

    public function transactions()
    {
        return $this->hasMany(
            MoneyTransaction::class,
            'reference_id',
            'iou_id'
        );
    }

    public function settlements()
    {
        return $this->hasMany(
            IouSettlement::class,
            'iou_id',
            'iou_id'
        );
    }

    public function claims()
    {
        return $this->hasMany(
            ClaimRequest::class,
            'iou_id',
            'iou_id'
        );
    }
}
