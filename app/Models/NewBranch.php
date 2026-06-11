<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewBranch extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'branch';
    protected $primaryKey = 'branch_id';
    public    $timestamps = true;

    protected $fillable = [
        'zone_id',
        'city_id',
        'branch_name',
        'branch_code',
        'manager_name',
        'contact_no',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'zone_id');
    }
    public function wallet()
    {
        return $this->hasOne(BranchWallet::class, 'branch_id', 'branch_id');
    }
}
