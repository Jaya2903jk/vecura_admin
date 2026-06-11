<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchWallet extends Model
{
    protected $table = 'branch_wallet';

    protected $primaryKey = 'wallet_id';

    public $timestamps = false;

    protected $fillable = [
        'branch_id',
        'current_balance',
        'total_credited',
        'total_debited',
        'last_updated'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}
