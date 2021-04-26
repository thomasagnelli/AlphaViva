<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is_self', function (User $user, Request $request) {
            return $request->user() && ($user->id == $request->input('id'));
        });

        Gate::define('is_self_client', function (User $user, Request $request) {
            return $user->id === $request->has('user_id');
        });

        Gate::define('admin', function (User $user, Request $request) {
            return $request->user() && ($request->user()->roleId == Role::getByName('admin'));
        });

        Gate::define('manager', function (User $user, Request $request) {
            return $request->user() && ($request->user()->roleId == Role::getByName('manager'));
        });

        Gate::define('client', function (User $user, Request $request) {
            return $request->user() && ($request->user()->roleId == Role::getByName('client'));
        });

        Gate::define('partner', function (User $user, Request $request) {
            return $request->user() && ($request->user()->roleId == Role::getByName('partner'));
        });
    }
}
