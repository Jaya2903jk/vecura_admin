<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcRequest extends Model
{
    protected $table      = 'pc_request';
    protected $primaryKey = 'request_id';
    public    $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'branch_id',
        'wallet_id',
        'raised_by',
        'requested_amount',
        'approved_amount',
        'reason',
        'urgency',
        'accounts_status',
        'mgmt_status',
        'transfer_ref',
        'transferred_at',
        'ticket_status',
        'wallet_balance_at_raise',
        'closed_at',
        'meta_data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'requested_amount'        => 'decimal:2',
        'approved_amount'         => 'decimal:2',
        'wallet_balance_at_raise' => 'decimal:2',
        'transferred_at'          => 'datetime',
        'closed_at'               => 'datetime',
        'created_at'              => 'datetime',
        'updated_at'              => 'datetime',
        'meta_data'               => 'array',   // auto json decode/encode
    ];

    // ── Relationships ─────────────────────────────────────────────────────

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
}
