<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;


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
        //

        Paginator::useBootstrapFive();
        
        Gate::define('is_admin', function(User $user){
            return $user->is_admin;
        });

        Gate::define('is_user', function(User $user){
            return !$user->is_admin;
        });
    }
}
