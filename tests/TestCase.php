<?php

namespace Offlineagency\LaravelWebex\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Offlineagency\LaravelWebex\LaravelWebexFacade;
use Offlineagency\LaravelWebex\Providers\LaravelWebexServiceProvider;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    public function getPackageProviders(
        $app
    ): array {
        return [
            LaravelWebexServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<string, class-string<\Illuminate\Support\Facades\Facade>>
     */
    public function getPackageAliases(
        $app
    ): array {
        return [
            'LaravelWebex' => LaravelWebexFacade::class,
        ];
    }
}
