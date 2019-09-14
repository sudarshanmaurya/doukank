<?php

namespace Badenjki\Seller\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class SellerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->make('Badenjki\Seller\Http\Controllers\RegistrationController');

    }
}
