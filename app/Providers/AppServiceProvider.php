<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //$this->registerPolicies();

        if (! $this->app->routesAreCached())
        {
            Passport::routes();
        }
        //
        \URL::forceScheme('https');
        Schema::defaultStringLength(191);
    }
}