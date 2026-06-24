<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;
use App\Models\EmployeeRole;
use App\Models\EmployeeDepartment;
use App\Models\HierarchyAccess;
use App\Models\UserMaster;

class RbacService
{
    public function assignRole($employeeId, $roleId, $assignedBy)
    {
        return EmployeeRole::updateOrCreate(
            ['employee_id' => $employeeId, 'role_id' => $roleId],
            ['assigned_by' => $assignedBy, 'assigned_at' => now(), 'is_active' => 1]
        );
    }

    public function assignDepartment($employeeId, $departmentId, $assignedBy)
    {
        return EmployeeDepartment::updateOrCreate(
            ['employee_id' => $employeeId, 'department_id' => $departmentId],
            ['assigned_by' => $assignedBy, 'assigned_at' => now(), 'is_active' => 1]
        );
    }

    public function assignHierarchyAccess($employeeId, $roleId, $zoneId, $branchId, $locationId, $assignedBy)
    {
        return HierarchyAccess::updateOrCreate(
            ['employee_id' => $employeeId, 'role_id' => $roleId],
            [
                'zone_id' => $zoneId,
                'branch_id' => $branchId,
                'location_id' => $locationId,
                'assigned_by' => $assignedBy,
                'assigned_at' => now(),
                'is_active' => 1
            ]
        );
    }

    public function getEmployeeRoles($employeeId)
    {
        return EmployeeRole::where('employee_id', $employeeId)->where('is_active', 1)->with('role')->get();
    }

    public function getEmployeeDepartments($employeeId)
    {
        return EmployeeDepartment::where('employee_id', $employeeId)->where('is_active', 1)->with('department')->get();
    }

    public function getEmployeeHierarchy($employeeId)
    {
        return HierarchyAccess::where('employee_id', $employeeId)->where('is_active', 1)->with(['role', 'zone', 'branch', 'location'])->get();
    }

    public function hasPermission($employeeId, $permissionName, $module = null)
    {
        $roles = EmployeeRole::where('employee_id', $employeeId)
            ->where('is_active', 1)
            ->pluck('role_id');

        if ($roles->isEmpty()) return false;

        $query = Permission::whereIn('id', function($q) use ($roles) {
            $q->select('permission_id')
                ->from('role_permissions')
                ->whereIn('role_id', $roles);
        })->where('name', $permissionName)->where('is_active', 1);

        if ($module) $query->where('module', $module);

        return $query->exists();
    }

    public function canAccessZone($employeeId, $zoneId)
    {
        return HierarchyAccess::where('employee_id', $employeeId)
            ->where('zone_id', $zoneId)
            ->where('is_active', 1)
            ->exists();
    }

    public function canAccessBranch($employeeId, $branchId)
    {
        return HierarchyAccess::where('employee_id', $employeeId)
            ->where('branch_id', $branchId)
            ->where('is_active', 1)
            ->exists();
    }

    public function canAccessLocation($employeeId, $locationId)
    {
        return HierarchyAccess::where('employee_id', $employeeId)
            ->where('location_id', $locationId)
            ->where('is_active', 1)
            ->exists();
    }

    public function isAdmin($employeeId)
    {
        return EmployeeRole::where('employee_id', $employeeId)
            ->whereHas('role', fn($q) => $q->where('name', 'Admin'))
            ->where('is_active', 1)
            ->exists();
    }

    public function isZoneManager($employeeId)
    {
        return EmployeeRole::where('employee_id', $employeeId)
            ->whereHas('role', fn($q) => $q->where('name', 'ZoneManager'))
            ->where('is_active', 1)
            ->exists();
    }

    public function isBranchManager($employeeId)
    {
        return EmployeeRole::where('employee_id', $employeeId)
            ->whereHas('role', fn($q) => $q->where('name', 'BranchManager'))
            ->where('is_active', 1)
            ->exists();
    }

    public function removeRole($employeeId, $roleId)
    {
        return EmployeeRole::where('employee_id', $employeeId)
            ->where('role_id', $roleId)
            ->update(['is_active' => 0]);
    }

    public function removeDepartment($employeeId, $departmentId)
    {
        return EmployeeDepartment::where('employee_id', $employeeId)
            ->where('department_id', $departmentId)
            ->update(['is_active' => 0]);
    }
}
