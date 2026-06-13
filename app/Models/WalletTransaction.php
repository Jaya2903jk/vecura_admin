<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table      = 'wallet_transaction';
    protected $primaryKey = 'txn_id';
    public    $timestamps = false;

    protected $fillable = [
        'wallet_id',
        'branch_id',
        'direction',
        'source_type',
        'source_id',
        'amount',
        'balance_before',
        'balance_after',
        'narration',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after'  => 'decimal:2',
        'created_at'     => 'datetime',
    ];

    public function wallet()
    {
        return $this->belongsTo(BranchWallet::class, 'wallet_id', 'wallet_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(UserMaster::class, 'created_by', 'UserID');
    }
}
