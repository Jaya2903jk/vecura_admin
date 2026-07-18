<?php

namespace App\Observers;

use App\Models\NewBranch;
use App\Services\MasterDataCacheService;

class NewBranchObserver
{
    public function created(NewBranch $newBranch): void
    {
        MasterDataCacheService::clearBranchesCache();
    }

    public function updated(NewBranch $newBranch): void
    {
        MasterDataCacheService::clearBranchesCache();
    }

    public function deleted(NewBranch $newBranch): void
    {
        MasterDataCacheService::clearBranchesCache();
    }

    public function restored(NewBranch $newBranch): void
    {
        MasterDataCacheService::clearBranchesCache();
    }

    public function forceDeleted(NewBranch $newBranch): void
    {
        MasterDataCacheService::clearBranchesCache();
    }
}
