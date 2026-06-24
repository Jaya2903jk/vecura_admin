<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeRole extends Pivot
{
    protected $table = 'employee_roles';
    protected $fillable = ['employee_id', 'role_id', 'assigned_by', 'assigned_at', 'is_active'];

    protected $casts = ['assigned_at' => 'datetime'];

    public function employee() { return $this->belongsTo(UserMaster::class, 'employee_id', 'UserID'); }
    public function role() { return $this->belongsTo(Role::class); }
    public function assignedBy() { return $this->belongsTo(UserMaster::class, 'assigned_by', 'UserID'); }
}
