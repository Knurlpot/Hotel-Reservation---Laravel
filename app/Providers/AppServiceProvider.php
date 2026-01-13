<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Import this for styling
use Illuminate\Support\Facades\Gate; // Import this for role checks

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Tell Laravel to use Bootstrap for your table pagination
        Paginator::useBootstrapFive();

        // 2. Define a "Gate" to check if the user is an Admin
        // This makes your @if checks in the Blade files much cleaner
        Gate::define('admin-only', function ($account) {
            return $account->role === 'Admin';
        });

        // 3. Define a "Gate" for Receptionists
        Gate::define('receptionist-only', function ($account) {
            return $account->role === 'Receptionist';
        });
    }
}
