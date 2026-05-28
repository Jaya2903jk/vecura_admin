<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseMaster extends Model
{
    protected $table = 'expense_master';

    protected $primaryKey = 'ExpenseId';

    public $timestamps = false;

    protected $fillable = [
        'ExpenseName',
        'Description',
        'Status',
        'CreatedBy',
        'CreatedDate',
    ];
}
