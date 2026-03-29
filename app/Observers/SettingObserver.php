<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingObserver
{
    /**
     * Handle the Setting "saved" event (covers both created and updated).
     */
    public function saved(Setting $setting): void
    {
        $this->clearSettingCaches();
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        $this->clearSettingCaches();
    }

    /**
     * Clear all settings related caches.
     */
    private function clearSettingCaches(): void
    {
        Cache::forget('company.settings');
        Cache::forget('company.about');

        Log::info('Settings caches cleared');
    }
}
