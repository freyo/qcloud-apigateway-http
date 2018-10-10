<?php

namespace Freyo\ApiGateway;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/apigateway.php', 'services.apigateway'
        );

        $this->app->singleton('apigateway', function ($app) {
            return new Application(
                $app['config']['services.apigateway']
            );
        });
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}