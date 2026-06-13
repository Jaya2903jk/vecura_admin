<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'sqlsrv';
    protected $table      = 'city';
    protected $primaryKey = 'city_id';
    public    $timestamps = true;

    protected $fillable = [
        'state_id',
        'city_name',
        'pincode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
}
