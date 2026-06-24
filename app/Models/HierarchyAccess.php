<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HierarchyAccess extends Model
{
    protected $table = 'hierarchy_access';
    protected $fillable = ['employee_id', 'role_id', 'zone_id', 'branch_id', 'location_id', 'assigned_by', 'assigned_at', 'is_active'];

    protected $casts = ['assigned_at' => 'datetime'];

    public function employee() { return $this->belongsTo(UserMaster::class, 'employee_id', 'UserID'); }
    public function role() { return $this->belongsTo(Role::class); }
    public function zone() { return $this->belongsTo(Zone::class, 'zone_id', 'zone_id'); }
    public function branch() { return $this->belongsTo(NewBranch::class, 'branch_id', 'branch_id'); }
    public function location() { return $this->belongsTo(Location::class, 'location_id', 'location_id'); }
    public function assignedBy() { return $this->belongsTo(UserMaster::class, 'assigned_by', 'UserID'); }
}
