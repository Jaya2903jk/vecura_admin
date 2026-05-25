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
        'amount',
        'bill_number',
        'bill_amount',
        'description',
    ];
}
