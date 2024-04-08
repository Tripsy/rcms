<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Gates are simply closures that determine if a user is authorized to perform a given action.
         * Typically, gates are defined within the boot method of the App\Providers\AuthServiceProvider class using the Gate facade.
         * Gates always receive a user instance as their first argument and may optionally receive additional arguments such as a relevant Eloquent model.
         *
         * https://laravel.com/docs/10.x/authorization#gates
         */
        Gate::define('isAdmin', function ($user) {
            return $user->isAdmin();
        });
    }
}
