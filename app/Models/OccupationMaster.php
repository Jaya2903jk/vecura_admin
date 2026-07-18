<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccupationMaster extends Model
{
    protected $table = 'Occupation_Master';
    protected $primaryKey = 'occupation_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'occupation_id',
        'occupation_name',
        'occupationType',
        'createdby',
        'modifiedby',
    ];
}
