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
    }
}
