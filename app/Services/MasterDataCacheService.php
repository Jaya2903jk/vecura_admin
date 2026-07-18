<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\NewBranch;
use App\Models\Designation;
use App\Models\IssueDepartment;
use App\Models\Role;

class MasterDataCacheService
{
    const CACHE_TTL = 3600; // 1 hour

    public static function getBranches()
    {
        return Cache::remember('master_branches', self::CACHE_TTL, function () {
            return NewBranch::where('is_active', 1)
                ->orderBy('branch_name')
                ->get();
        });
    }

    public static function getDesignations()
    {
        return Cache::remember('master_designations', self::CACHE_TTL, function () {
            return Designation::orderBy('Designation')
                ->get();
        });
    }

    public static function getDepartments()
    {
        return Cache::remember('master_departments', self::CACHE_TTL, function () {
            return IssueDepartment::orderBy('DepartmentName')
                ->get();
        });
    }

    public static function getRoles()
    {
        return Cache::remember('master_roles', self::CACHE_TTL, function () {
            return Role::where('is_active', 1)
                ->orderBy('name')
                ->get();
        });
    }

    public static function getAllMasterData()
    {
        return [
            'branches' => self::getBranches(),
            'designations' => self::getDesignations(),
            'departments' => self::getDepartments(),
            'roles' => self::getRoles(),
        ];
    }

    // Clear cache methods
    public static function clearBranchesCache()
    {
        Cache::forget('master_branches');
    }

    public static function clearDesignationsCache()
    {
        Cache::forget('master_designations');
    }

    public static function clearDepartmentsCache()
    {
        Cache::forget('master_departments');
    }

    public static function clearRolesCache()
    {
        Cache::forget('master_roles');
    }

    public static function clearAllMasterCache()
    {
        self::clearBranchesCache();
        self::clearDesignationsCache();
        self::clearDepartmentsCache();
        self::clearRolesCache();
    }
}
