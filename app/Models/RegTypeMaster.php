<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegTypeMaster extends Model
{
    protected $table = 'RegTypeMaster';
    protected $primaryKey = 'RegTypeId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'RegTypeId',
        'RegType',
    ];
}
