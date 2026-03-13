<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Mockery;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\TestCase;

class LaravelWebexFacadeTest extends TestCase
{
    /** @test */
    public function it_loads_facade_alias()
    {
        $this->app->singleton('laravel-webex', function ($app) {
            return Mockery::mock(LaravelWebex::class, function ($mock) {
                $mock->shouldReceive('test')->once();
            });
        });

        \LaravelWebex::test();

        $this->assertInstanceOf(LaravelWebex::class, app('laravel-webex'));
    }
}
