<?php

namespace App\Http\Middleware;

use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareSettingsData
{
    protected $homeService;

    public function __construct(SettingsService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        Inertia::share([
            'settings' => fn() => $this->homeService->getSettings()
        ]);

        return $next($request);
    }
}
