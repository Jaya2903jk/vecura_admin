<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IouSettlementItem extends Model
{
    protected $table = 'iou_settlement_items';

    protected $primaryKey = 'item_id';

    public $timestamps = false;

    protected $fillable = [
        'settlement_id',
        'expense_type',
        'bill_date',
        'bill_amount',
        'settlement_amount',
        'employee_claim_amount',
        'bill_file',
        'remarks',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'bill_amount' => 'decimal:2',
        'settlement_amount' => 'decimal:2',
        'employee_claim_amount' => 'decimal:2',
    ];

    public function settlement()
    {
        return $this->belongsTo(
            IouSettlement::class,
            'settlement_id',
            'settlement_id'
        );
    }

}
