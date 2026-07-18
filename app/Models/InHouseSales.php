<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InHouseSales extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'sale_date',
        'day_target',
        'day_sales',
        'visits',
        'joined',
        'packages_sold',
        'notes',
        'entered_by',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'day_target' => 'decimal:2',
        'day_sales' => 'decimal:2',
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
