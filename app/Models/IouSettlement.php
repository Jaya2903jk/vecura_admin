<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IouSettlement extends Model
{
    protected $table = 'iou_settlements';

    protected $primaryKey = 'settlement_id';

    public $timestamps = false;

    protected $fillable = [
        'iou_id',
        'ticket_id',
        'employee_id',
        'settlement_date',
        'actual_expense',
        'returned_amount',
        'extra_claim_amount',
        'remarks',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'settlement_date' => 'date',
        'actual_expense' => 'decimal:2',
        'returned_amount' => 'decimal:2',
        'extra_claim_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    public function iou()
    {
        return $this->belongsTo(
            IouRequest::class,
            'iou_id',
            'iou_id'
        );
    }

    public function employee()
    {
        return $this->belongsTo(
            UserMaster::class,
            'employee_id',
            'UserID'
        );
    }

    public function transactions()
    {
        return $this->hasMany(
            MoneyTransaction::class,
            'reference_id',
            'settlement_id'
        )->where('type', 'iou_settlement');
    }
}
