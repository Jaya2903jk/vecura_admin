<?php

namespace App\Observers;

use App\Models\IssueDepartment;
use App\Services\MasterDataCacheService;

class IssueDepartmentObserver
{
    public function created(IssueDepartment $issueDepartment): void
    {
        MasterDataCacheService::clearDepartmentsCache();
    }

    public function updated(IssueDepartment $issueDepartment): void
    {
        MasterDataCacheService::clearDepartmentsCache();
    }

    public function deleted(IssueDepartment $issueDepartment): void
    {
        MasterDataCacheService::clearDepartmentsCache();
    }

    public function restored(IssueDepartment $issueDepartment): void
    {
        MasterDataCacheService::clearDepartmentsCache();
    }

    public function forceDeleted(IssueDepartment $issueDepartment): void
    {
        MasterDataCacheService::clearDepartmentsCache();
    }
}
