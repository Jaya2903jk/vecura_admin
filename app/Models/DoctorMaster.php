<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorMaster extends Model
{
    protected $table = 'Doctor_Master';

    protected $primaryKey = 'Doctor_Id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'Doctor_Id',
        'DoctorName',
        'Locations',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate',
        'Status',
        'UserCode',
        'CommonCode',
    ];

    public function userMaster()
    {
        return $this->belongsTo(UserMaster::class, 'UserCode', 'UserCode');
    }
}
