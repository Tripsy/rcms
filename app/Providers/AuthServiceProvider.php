<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * Instead of manually registering model policies, Laravel can automatically discover policies as long as the model and policy follow standard Laravel naming conventions.
     * Specifically, the policies must be in a Policies directory at or above the directory that contains your models.
     * So, for example, the models may be placed in the app/Models directory while the policies may be placed in the app/Policies directory.
     * In this situation, Laravel will check for policies in app/Models/Policies then app/Policies. In addition, the policy name must match the model name
     * and have a Policy suffix. So, a User model would correspond to a UserPolicy policy class.
     *
     * https://laravel.com/docs/10.x/authorization#creating-policies
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //        Project::class => ProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
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
