<?php

namespace Jlab\Taxonomy;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        //$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Must include the bindings middleware in order to get 
        // route model binding in a package like this.
        Route::group([
            'middleware' => ['bindings'],
            'prefix' => '',
            'namespace' => '',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views','taxonomy');

        $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__ . '/../database/factories');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}