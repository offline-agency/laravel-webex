<?php

namespace Offlineagency\LaravelWebex\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Offlineagency\LaravelWebex\LaravelWebexFacade;
use Offlineagency\LaravelWebex\Providers\LaravelWebexServiceProvider;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  Application  $app
     * @return array<int, class-string<ServiceProvider>>
     */
    public function getPackageProviders(
        $app
    ): array {
        return [
            LaravelWebexServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     * @return array<string, class-string<Facade>>
     */
    public function getPackageAliases(
        $app
    ): array {
        return [
            'LaravelWebex' => LaravelWebexFacade::class,
        ];
    }
}
