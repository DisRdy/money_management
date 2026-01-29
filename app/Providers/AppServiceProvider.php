<?php

namespace App\Providers;

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
        // Authorization Gates
        \Illuminate\Support\Facades\Gate::define('manage-family', function ($user) {
            return $user->isOwner();
        });

        // Audit Log Gate - Owner only
        \Illuminate\Support\Facades\Gate::define('view-audit-logs', function ($user) {
            return $user->isOwner();
        });

        // Register Observers
        \App\Models\Transaction::observe(\App\Observers\TransactionObserver::class);
    }
}
