<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultantSales extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'sale_date',
        'sales_amount',
        'consultations',
        'consultant_name',
        'notes',
        'entered_by',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'sales_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }

    public function branch()
    {
        return $this->belongsTo(NewBranch::class, 'branch_id', 'branch_id');
    }
}
