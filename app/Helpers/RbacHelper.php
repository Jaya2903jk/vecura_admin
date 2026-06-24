<?php

namespace App\Helpers;

use App\Models\EmployeeRole;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RbacHelper
{
    /**
     * Check if current user has permission
     */
    public static function hasPermission($permissionName, $module = null)
    {
        $userId = session('user_id');
        if (!$userId) return false;

        $roles = EmployeeRole::where('employee_id', $userId)
            ->where('is_active', 1)
            ->pluck('role_id');

        if ($roles->isEmpty()) return false;

        $query = Permission::whereIn('id', function($q) use ($roles) {
            $q->select('permission_id')
                ->from('role_permissions')
                ->whereIn('role_id', $roles);
        })->where('name', $permissionName)->where('is_active', 1);

        if ($module) {
            $query->where('module', $module);
        }

        return $query->exists();
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($roleName)
    {
        return in_array($roleName, session('rbac_roles', []));
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        return session('is_admin', false);
    }

    /**
     * Get user's RBAC role name
     */
    public static function getRoleName()
    {
        return session('rbac_role_name', 'Employee');
    }

    /**
     * Get all user permissions for a module
     */
    public static function getModulePermissions($module)
    {
        $userId = session('user_id');
        if (!$userId) return [];

        $roles = EmployeeRole::where('employee_id', $userId)
            ->where('is_active', 1)
            ->pluck('role_id');

        if ($roles->isEmpty()) return [];

        return Permission::whereIn('id', function($q) use ($roles) {
            $q->select('permission_id')
                ->from('role_permissions')
                ->whereIn('role_id', $roles);
        })->where('module', $module)->where('is_active', 1)->pluck('name')->toArray();
    }

    /**
     * Check if user has permission for ANY of the given modules
     */
    public static function hasPermissionForAny($permissionName, $modules = [])
    {
        if (empty($modules)) return false;

        foreach ($modules as $module) {
            if (self::hasPermission($permissionName, $module)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get modules grouped by parent (dynamic from database)
     * Returns: ['Masters' => [...modules], 'RBAC' => [...modules], etc]
     */
    public static function getModulesByParent($parent = 'Masters')
    {
        return \App\Models\Module::where('parent', $parent)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get all parent categories from modules table
     */
    public static function getAllParentCategories()
    {
        return \App\Models\Module::where('is_active', 1)
            ->distinct()
            ->pluck('parent')
            ->filter()
            ->sort()
            ->values();
    }

    /**
     * Get master modules dynamically (from database parent='Masters')
     */
    public static function getMasterModules()
    {
        return self::getModulesByParent('Masters');
    }

    /**
     * Get all modules (for admin/reference)
     */
    public static function getAllModules()
    {
        return \App\Models\Module::where('is_active', 1)->get();
    }

    /**
     * Get master module names (dynamic from database)
     */
    public static function getMasterModuleNames()
    {
        return \App\Models\Module::where('parent', 'Masters')
            ->where('is_active', 1)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Check if user can perform action on module
     * Actions: read, create, edit, delete, approve
     */
    public static function canPerformAction($action, $module)
    {
        if (self::isAdmin()) return true;
        return self::hasPermission($action, $module);
    }

    /**
     * Get allowed actions for module
     */
    public static function getAllowedActions($module)
    {
        if (self::isAdmin()) {
            return ['read', 'create', 'edit', 'delete', 'approve'];
        }

        $userId = session('user_id');
        if (!$userId) return [];

        $roles = EmployeeRole::where('employee_id', $userId)
            ->where('is_active', 1)
            ->pluck('role_id');

        if ($roles->isEmpty()) return [];

        return Permission::whereIn('id', function($q) use ($roles) {
            $q->select('permission_id')
                ->from('role_permissions')
                ->whereIn('role_id', $roles);
        })->where('module', $module)->where('is_active', 1)->pluck('name')->toArray();
    }
}
