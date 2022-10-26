<?php

namespace Offlineagency\LaravelWebex\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Offlineagency\LaravelWebex\LaravelWebexFacade;
use Offlineagency\LaravelWebex\Providers\LaravelWebexServiceProvider;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function getPackageProviders(
        $app
    ): array {
        return [
            LaravelWebexServiceProvider::class,
        ];
    }

    public function getPackageAliases(
        $app
    ): array {
        return [
            'LaravelWebex' => LaravelWebexFacade::class,
        ];
    }
}
