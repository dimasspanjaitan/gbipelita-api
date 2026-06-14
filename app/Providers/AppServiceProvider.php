<?php

namespace App\Providers;

use App\Gates\LookupGate;
use App\Models\Setting;
use App\Observers\SettingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Setting::observe(SettingObserver::class);
        LookupGate::register();
    }
}
