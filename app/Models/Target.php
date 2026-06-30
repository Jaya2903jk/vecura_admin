<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'target_type',
        'target_amount',
        'effective_from',
        'effective_to',
        'description',
        'created_by',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'target_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }

    public function branch()
    {
        return $this->belongsTo(NewBranch::class, 'branch_id', 'id');
    }
}
