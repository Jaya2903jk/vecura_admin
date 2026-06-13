<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcBillItem extends Model
{
    protected $table      = 'pc_bill_item';
    protected $primaryKey = 'item_id';
    public    $timestamps = false;
    const CREATED_AT      = 'created_at';

    protected $fillable = [
        'submission_id',
        'expense_id',
        'bill_description',
        'bill_number',
        'bill_date',
        'amount',
        'attachment_path',
        'item_status',
        'created_at',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'bill_date'  => 'date',
        'created_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(PcBillSubmission::class, 'submission_id', 'submission_id');
    }

    public function expense()
    {
        return $this->belongsTo(ExpenseMaster::class, 'expense_id', 'ExpenseId');
    }
}