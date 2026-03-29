<?php

namespace App\Observers;

use App\Models\CompanyInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CompanyInfoObserver
{
    /**
     * Handle the CompanyInfo "saved" event (covers both created and updated).
     */
    public function saved(CompanyInfo $companyInfo): void
    {
        $this->clearCompanyInfoCaches();
    }

    /**
     * Handle the CompanyInfo "deleted" event.
     */
    public function deleted(CompanyInfo $companyInfo): void
    {
        $this->clearCompanyInfoCaches();
    }

    /**
     * Clear all company info related caches.
     */
    private function clearCompanyInfoCaches(): void
    {
        Cache::forget('company.about');
        Cache::forget('company.settings');

        Log::info('Company info caches cleared');
    }
}
