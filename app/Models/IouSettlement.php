<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IouSettlement extends Model
{
    use HasFactory;

    protected $table = 'iou_settlements';

    protected $primaryKey = 'settlement_id';

    protected $fillable = [
        'ticket_id',
        'employee_id',
        'current_balance',
        'total_bill_amount',
        'company_settlement_amount',
        'employee_claim_amount',
        'remaining_balance',
        'settlement_type',
        'settlement_status',
        'remarks',
        'created_by',
        'approved_by',
        'approved_at',
        'meta_data',
        'claim_transfer_amount',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'total_bill_amount' => 'decimal:2',
        'company_settlement_amount' => 'decimal:2',
        'employee_claim_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'approved_at' => 'datetime',
        'meta_data' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'employee_id');
    }

    public function ticket()
    {
        return $this->belongsTo(IssueMaster::class, 'ticket_id');
    }

    public function creator()
    {
        return $this->belongsTo(UserMaster::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(UserMaster::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(IouSettlementItem::class, 'settlement_id', 'settlement_id');
    }
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('settlement_status', 'PENDING');
    }

    public function scopeApproved($query)
    {
        return $query->where('settlement_status', 'APPROVED');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsApprovedAttribute()
    {
        return $this->settlement_status === 'APPROVED';
    }
}
