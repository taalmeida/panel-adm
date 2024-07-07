<?php

namespace App\Providers;

use App\Models\{User, Permission, Role};
use App\Policies\{UserPolicy, RolePolicy, PermissionPoliciy};
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
//here 
   protected $policies = [

    User::class => UserPolicy::class,
    Permission::class => PermissonPolicy::class,
    Role::class => RolePolicy::class,

   ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
