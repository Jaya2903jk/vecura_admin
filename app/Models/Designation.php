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

    public function departments()
    {
        return $this->belongsToMany(
            IssueDepartment::class,
            'designation_department',
            'designation_code',
            'department_id',
            'DesignationCode',
            'Departmentid'
        );
    }

    public function departmentMappings()
    {
        return $this->hasMany(DesignationDepartment::class, 'designation_code', 'DesignationCode');
    }
}
