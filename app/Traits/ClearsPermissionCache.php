<?php

namespace App\Traits;

trait ClearsPermissionCache
{
    protected function clearPermissionCache()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}