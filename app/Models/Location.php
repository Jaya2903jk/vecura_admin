<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'location';
    protected $primaryKey = 'location_id';
    public    $timestamps = true;

    protected $fillable = [
        'branch_id',
        'city_id',
        'location_name',
        'address',
        'pincode',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function branch()
    {
        return $this->belongsTo(NewBranch::class, 'branch_id', 'branch_id');
    }
}
