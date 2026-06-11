<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'country';
    protected $primaryKey = 'country_id';
    public    $timestamps = true;

    protected $fillable = [
        'country_name',
        'country_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'country_id');
    }

    public function zones()
    {
        return $this->hasMany(Zone::class, 'country_id', 'country_id');
    }
}
