<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get all landing profiles for landing page.
     */
    public function getSettings(): ?array
    {
        $cacheEnabled = config('app.settings_cache_enabled', false);

        logger()->info('Settings cache enabled: ' . ($cacheEnabled ? 'true' : 'false'));

        if (!$cacheEnabled) {
            $setting = Setting::latest()->first();
            return $setting ? $setting->toArray() : null;
        }

        return Cache::remember('settings', now()->addHour(), function () {
            $setting = Setting::latest()->first();
            return $setting ? $setting->toArray() : null;
        });
    }
}
