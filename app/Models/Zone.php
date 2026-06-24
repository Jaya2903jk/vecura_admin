<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'zone';
    protected $primaryKey = 'zone_id';
    public    $timestamps = true;

    protected $fillable = [
        'country_id',
        'zone_name',
        'zone_code',
        'region_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
    
}
