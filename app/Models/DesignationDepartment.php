<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignationDepartment extends Model
{
    protected $table = 'designation_department';

    protected $fillable = [
        'designation_id',
        'designation_code',
        'department_id',
        'is_active',
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_code', 'DesignationCode');
    }

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'department_id', 'Departmentid');
    }
}
