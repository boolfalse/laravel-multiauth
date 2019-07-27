<?php

namespace App\Providers;

use App\Models\Admin;
use App\Policies\AdminRolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Admin::class => AdminRolePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('for_manager', 'AdminRolePolicy@for_manager');
        Gate::define('for_moderator', 'AdminRolePolicy@for_moderator');
        Gate::define('for_administrator', 'AdminRolePolicy@for_administrator');
    }
}
