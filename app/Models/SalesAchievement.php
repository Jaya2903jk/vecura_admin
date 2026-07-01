<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesAchievement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'department_id',
        'achievement_date',
        'achieved_amount',
        'visits',
        'conversions',
        'achievement_type',
        'notes',
        'entered_by',
    ];

    protected $casts = [
        'achievement_date' => 'date',
        'achieved_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }

    public function branch()
    {
        return $this->belongsTo(NewBranch::class, 'branch_id', 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'department_id', 'Departmentid');
    }
}
