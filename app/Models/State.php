<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'state';
    protected $primaryKey = 'state_id';
    public    $timestamps = true;

    protected $fillable = [
        'country_id',
        'state_name',
        'state_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id', 'state_id');
    }
}
