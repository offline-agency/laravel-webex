<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Support\ServiceProvider;

class LaravelWebexServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/webex.php' => config_path('webex.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/webex.php',
            'webex'
        );

        // Register the main class to use with the facade
        $this->app->singleton('laravel-webex', function () {
            return new LaravelWebex();
        });
    }
}
