<?php

namespace App\Traits;

use App\Models\EmployeeRole;
use App\Models\EmployeeDepartment;
use App\Models\HierarchyAccess;
use App\Services\RbacService;

trait HasRbac
{
    public function roles() {
        return $this->belongsToMany(\App\Models\Role::class, 'employee_roles', 'employee_id', 'role_id');
    }

    public function departments() {
        return $this->belongsToMany(\App\Models\IssueDepartment::class, 'employee_departments', 'employee_id', 'department_id');
    }

    public function hierarchyAccess() {
        return $this->hasMany(HierarchyAccess::class, 'employee_id', 'UserID');
    }

    public function hasRole($roleName) {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission($permissionName, $module = null) {
        $rbac = app(RbacService::class);
        return $rbac->hasPermission($this->UserID, $permissionName, $module);
    }

    public function isAdmin() {
        return $this->hasRole('Admin');
    }

    public function canAccess($zoneId = null, $branchId = null, $locationId = null) {
        $rbac = app(RbacService::class);
        if ($zoneId && !$rbac->canAccessZone($this->UserID, $zoneId)) return false;
        if ($branchId && !$rbac->canAccessBranch($this->UserID, $branchId)) return false;
        if ($locationId && !$rbac->canAccessLocation($this->UserID, $locationId)) return false;
        return true;
    }
}
