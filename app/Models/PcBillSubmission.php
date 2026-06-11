<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcBillSubmission extends Model
{
    protected $table      = 'pc_bill_submission';
    protected $primaryKey = 'submission_id';
    const CREATED_AT      = 'created_at';
    const UPDATED_AT      = 'updated_at';

    protected $fillable = [
        'ticket_id',
        'branch_id',
        'wallet_id',
        'raised_by',
        // 'category',
        'description',
        'total_bill_amount',
        'approved_amount',
        'accounts_status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'accounts_remarks',
        'ticket_status',
        'wallet_balance_at_raise',
        'closed_at',
        'meta_data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'total_bill_amount'       => 'decimal:2',
        'approved_amount'         => 'decimal:2',
        'wallet_balance_at_raise' => 'decimal:2',
        'reviewed_at'             => 'datetime',
        'closed_at'               => 'datetime',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime',
        'meta_data'               => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(NewBranch::class, 'branch_id', 'branch_id');
    }

    public function wallet()
    {
        return $this->belongsTo(BranchWallet::class, 'wallet_id', 'wallet_id');
    }

    public function raisedBy()
    {
        return $this->belongsTo(UserMaster::class, 'raised_by', 'UserID');
    }

    public function items()
    {
        return $this->hasMany(PcBillItem::class, 'submission_id', 'submission_id');
    }
}
