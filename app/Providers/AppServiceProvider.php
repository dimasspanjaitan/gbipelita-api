<?php

namespace App\Providers;

use App\Models\Action;
use App\Models\Module;
use App\Models\Setting;
use App\Observers\ActionObserver;
use App\Observers\ModuleObserver;
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
        Setting::observe(SettingObserver::class);
        Module::observe(ModuleObserver::class);
        Action::observe(ActionObserver::class);
    }
}
