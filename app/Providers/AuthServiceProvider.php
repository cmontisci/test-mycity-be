<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
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
        $this->registerPolicies();

        Passport::tokensCan([
            'user-access' => 'User Type',
            'client-access' => 'Client Type',
        ]);

        Gate::define('user-access', function ($user) {
            return $user instanceof User;
        });

        Gate::define('client-access', function ($user) {
            return $user instanceof Client;
        });
    }
}
