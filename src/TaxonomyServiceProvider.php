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
        $this->loadViewsFrom(__DIR__ . '/../resources/views','taxonomy');

        /*
         * Routes
         */
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        // Must include the bindings middleware in order to get 
        // route model binding in a package like this.
        Route::group([
            'middleware' => ['bindings'],
            'prefix' => '',
            'namespace' => '',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });


        /*
         * Items we publish so that the user can override in his/her own application
         */
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/jlab/taxonomy'),
        ]);
        $this->publishes([
            __DIR__.'/../resources/assets/js/' => public_path('vendor/jlab/taxonomy/js/'),
        ], 'public');


        /*
         * Publish our factory extensions for the convenience of a customer who wants to test
         * models with taxonomy dependencies.
         */
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