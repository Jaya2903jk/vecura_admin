<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnownByMaster extends Model
{
    protected $table = 'KnownBy';
    protected $primaryKey = 'Knwid';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'Knwid',
        'KnwCode',
        'KwnBy',
        'kstatus',
        'digital',
        'CreatedBy',
        'ModifiedBy',
    ];
}
