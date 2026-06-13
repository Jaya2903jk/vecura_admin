<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBalance extends Model
{
    use HasFactory;

    protected $table = 'employee_balances';

    protected $primaryKey = 'balance_id';

    protected $fillable = [

        'employee_id',
        'total_iou_amount',
        'total_settlement_amount',
        'total_claim_amount',
        'pending_balance',
        'pending_claim_amount',
    ];
}
