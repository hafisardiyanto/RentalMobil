<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manage_cars', function (User $user) {
            if ($user->role === 'owner')
                return true;
            return $user->role === 'admin' && in_array('manage_cars', $user->adminRole->permissions ?? []);
        });

        Gate::define('manage_bookings', function (User $user) {
            if ($user->role === 'owner')
                return true;
            return $user->role === 'admin' && in_array('manage_bookings', $user->adminRole->permissions ?? []);
        });

        Gate::define('view_reports', function (User $user) {
            if ($user->role === 'owner')
                return true;
            return $user->role === 'admin' && in_array('view_reports', $user->adminRole->permissions ?? []);
        });
    }
}
