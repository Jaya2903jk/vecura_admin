<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentFor extends Model
{
    protected $table = 'ApppointmentFor';

    protected $primaryKey = 'AppointtCode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'AppointtCode',
        'AppointName',
        'AppStatus',
    ];
}