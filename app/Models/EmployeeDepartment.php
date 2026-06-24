<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeDepartment extends Pivot
{
    protected $table = 'employee_departments';
    protected $fillable = ['employee_id', 'department_id', 'assigned_by', 'assigned_at', 'is_active'];

    protected $casts = ['assigned_at' => 'datetime'];

    public function employee() { return $this->belongsTo(UserMaster::class, 'employee_id', 'UserID'); }
    public function department() { return $this->belongsTo(IssueDepartment::class, 'department_id', 'Departmentid'); }
    public function assignedBy() { return $this->belongsTo(UserMaster::class, 'assigned_by', 'UserID'); }
}
