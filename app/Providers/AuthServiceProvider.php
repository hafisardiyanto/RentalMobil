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

        $permissions = [
            'view_cars',
            'create_cars',
            'edit_cars',
            'delete_cars',
            'view_bookings',
            'edit_bookings',
            'manage_fines',
            'delete_bookings',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                if ($user->role === 'owner')
                    return true;
                return $user->role === 'admin' && in_array($permission, $user->adminRole->permissions ?? []);
            });
        }
    }
}
