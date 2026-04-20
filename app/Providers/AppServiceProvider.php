<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use App\Interfaces\Services\OrderServiceInterface;
use App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Interfaces\Repositories\AdminOrderRepositoryInterface;
use App\Interfaces\Repositories\CartRepositoryInterface;
use App\Interfaces\Repositories\UserOrderRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Interfaces\Services\CartServiceInterface;
use App\Repositories\CartRepository;
use App\Services\CartService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->bind(AdminOrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(UserOrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);

        $this->app->bind(CartServiceInterface::class, CartService::class);

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

        Gate::define('viewAsCustomer', function ($user) {
            return $user->is_admin && session('as_customer') === true;
        });

        if (app()->isLocal()) {
        Model::preventLazyLoading();

        Model::handleLazyLoadingViolationUsing(function (Model $model, string $relation) {
            logger()->warning(
                "Lazy loading detected",
                [
                    'model' => $model::class,
                    'relation' => $relation,
                ]
            );
        });
        }

    }
}
