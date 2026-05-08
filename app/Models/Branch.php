<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branchmaster'; // change if your table name is different

    protected $primaryKey = 'Id'; // your PK is "Id"

    public $timestamps = false; // since you're not using Laravel timestamps

    protected $fillable = [
        'Branchname',
        'Status',
        'DateAdded',
        'DateUpdated',
        'CreatedBy',
        'UpdatedBy',
        'LocId',
        'saralBranch',
        'ERPLocationCode',
        'ERPLocation'
    ];

    public function scopeActive($query)
    {
        return $query->where('Status', 0);
    }
}