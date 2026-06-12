<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    private function transform(Setting $setting): array
    {
        $data = $setting->toArray();
        $extra = $data['data'] ?? [];
        unset($data['data']);
        return array_merge($data, $extra);
    }

    /**
     * Get all landing profiles for landing page.
     */
    public function getSettings(): ?array
    {
        $callback = function () {
            $setting = Setting::latest()->first();

            return $setting ? $this->transform($setting) : null;
        };

        return config('app.settings_cache_enabled') ? Cache::remember('settings', now()->addHour(), $callback) : $callback();
    }
}
