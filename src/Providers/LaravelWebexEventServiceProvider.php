<?php

namespace Offlineagency\LaravelWebex\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;

class LaravelWebexEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SuccessfulAuthentication::class => [
            //
        ]
    ];
}
