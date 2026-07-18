<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateMaster extends Model
{
    // StateMaster.php
    protected $table = 'State_Master';
    protected $primaryKey = 'state_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'LocationName',
        'LocationCode',

    ];
}
