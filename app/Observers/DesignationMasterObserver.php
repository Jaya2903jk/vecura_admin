<?php

namespace App\Observers;

use App\Models\Designation;
use App\Services\MasterDataCacheService;

class DesignationMasterObserver
{
    public function created(Designation $designation): void
    {
        MasterDataCacheService::clearDesignationsCache();
    }

    public function updated(Designation $designation): void
    {
        MasterDataCacheService::clearDesignationsCache();
    }

    public function deleted(Designation $designation): void
    {
        MasterDataCacheService::clearDesignationsCache();
    }

    public function restored(Designation $designation): void
    {
        MasterDataCacheService::clearDesignationsCache();
    }

    public function forceDeleted(Designation $designation): void
    {
        MasterDataCacheService::clearDesignationsCache();
    }
}
