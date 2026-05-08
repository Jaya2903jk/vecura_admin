<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model {
    protected $table = 'DesignationMaster';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'DesignationCode',
        'Designation',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate',
        'status'
    ];

}
