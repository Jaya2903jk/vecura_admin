<?php

namespace App\Helpers;

use App\Models\Module;

class MenuHelper
{
    /**
     * Get menu structure grouped by parent category
     * Returns: ['Masters' => [...modules], 'RBAC' => [...modules], 'Ungrouped' => [...modules]]
     */
    public static function getMenuStructure($userOnly = false)
    {
        try {
            $query = Module::where('is_active', 1)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc');
        } catch (\Exception $e) {
            // Fallback if sort_order column doesn't exist
            $query = Module::where('is_active', 1)->orderBy('name', 'asc');
        }
        
        if ($userOnly && !session('is_admin')) {
            // For non-admin users, only show modules they have permission for
            $moduleNames = \App\Models\Permission::where('role_permissions.role_id', function($q) {
                // Get all roles assigned to this user
                $userId = session('user_id');
                $q->from('employee_roles')
                  ->whereRaw('employee_roles.role_id = role_permissions.role_id')
                  ->where('employee_roles.employee_id', $userId);
            })
            ->distinct('module')
            ->pluck('module')
            ->toArray();

            if (empty($moduleNames)) {
                return [];
            }
            
            $query->whereIn('name', $moduleNames);
        }

        $modules = $query->get();

        // Group by parent
        $grouped = [];
        foreach ($modules as $module) {
            $parent = $module->parent ?? 'Other';
            if (!isset($grouped[$parent])) {
                $grouped[$parent] = [];
            }
            $grouped[$parent][] = $module;
        }

        return $grouped;
    }

    /**
     * Check if user has permission to view a module
     */
    public static function hasModuleAccess($moduleName)
    {
        if (session('is_admin')) {
            return true;
        }

        return \App\Helpers\RbacHelper::hasPermission('read', $moduleName);
    }

    /**
     * Get safe route URL for module
     */
    public static function getModuleUrl($module)
    {
        if (!$module->route_prefix) {
            return '#';
        }

        try {
            return route($module->route_prefix . '.index');
        } catch (\Exception $e) {
            return '#';
        }
    }
}
