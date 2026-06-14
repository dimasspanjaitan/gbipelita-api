<?php

namespace App\Gates;

use Illuminate\Support\Facades\Gate;

class LookupGate
{
    public static function register(): void
    {
        Gate::define('lookup-role', function ($user) {
            return $user->can('create-user')
                || $user->can('update-user');
        });
        Gate::define('lookup-user', function ($user) {
            return $user->can('create-user-position')
                || $user->can('update-user-position');
        });
        Gate::define('lookup-department', function ($user) {
            return $user->can('create-user')
                || $user->can('update-user');
        });
        Gate::define('lookup-division', function ($user) {
            return $user->can('create-user')
                || $user->can('update-user');
        });
        Gate::define('lookup-volunteer', function ($user) {
            return $user->can('update-schedule');
        });
    }
}
