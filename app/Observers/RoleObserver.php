<?php

namespace App\Observers;

use App\Models\Role;
use App\Services\MasterDataCacheService;

class RoleObserver
{
    public function created(Role $role): void
    {
        MasterDataCacheService::clearRolesCache();
    }

    public function updated(Role $role): void
    {
        MasterDataCacheService::clearRolesCache();
    }

    public function deleted(Role $role): void
    {
        MasterDataCacheService::clearRolesCache();
    }

    public function restored(Role $role): void
    {
        MasterDataCacheService::clearRolesCache();
    }

    public function forceDeleted(Role $role): void
    {
        MasterDataCacheService::clearRolesCache();
    }
}
