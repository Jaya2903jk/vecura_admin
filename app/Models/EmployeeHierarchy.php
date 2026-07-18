<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHierarchy extends Model
{
    protected $table = 'employee_hierarchy';

    protected $fillable = [
        'employee_id',
        'manager_id',
        'department_id',
        'is_active',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'employee_id', 'UserID');
    }

    public function manager()
    {
        return $this->belongsTo(UserMaster::class, 'manager_id', 'UserID');
    }

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'department_id', 'Departmentid');
    }
}
