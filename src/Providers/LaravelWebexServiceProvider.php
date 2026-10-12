<?php

namespace Offlineagency\LaravelWebex\Providers;

use Illuminate\Support\ServiceProvider;
use Offlineagency\LaravelWebex\LaravelWebex;

class LaravelWebexServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $route = config('webex.auth_route');
        if (! is_array($route) || ($route['enabled'] ?? true) !== false) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/webex.php' => config_path('webex.php'),
            ], ['config', 'webex-config']);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/webex.php',
            'webex'
        );

        // Register the main class to use with the facade
        $this->app->singleton('laravel-webex', function () {
            return new LaravelWebex;
        });

        $this->app->register(LaravelWebexEventServiceProvider::class);
    }
}
