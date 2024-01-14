<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('owner', function (User $user) {
            return $user->roles->contains(Role::IS_OWNER);
        });

        Gate::define('admin', function (User $user) {
            return $user->roles->contains(Role::IS_ADMIN);
        });

        Gate::define('doctor', function (User $user) {
            return $user->roles->contains(Role::IS_DOCTOR);
        });
    }
}
