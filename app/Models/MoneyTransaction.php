<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransaction extends Model
{
    use HasFactory;

    protected $table = 'money_transactions';

    const UPDATED_AT = null;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [

        'employee_id',
        'ticket_id',
        'reference_id',
        'type',
        'amount',
        'remarks',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(UserMaster::class, 'created_by');
    }
}
