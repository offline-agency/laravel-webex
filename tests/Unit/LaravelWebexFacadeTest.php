<?php

use Offlineagency\LaravelWebex\LaravelWebex;

describe('LaravelWebexFacade', function () {
    it('loads facade alias', function () {
        $this->app->singleton(
            'laravel-webex',
            function ($app) {
                return \Mockery::mock(LaravelWebex::class, function ($mock) {
                    $mock->shouldReceive('test');
                });
            }
        );

        \LaravelWebex::test();

        expect(true)->toBeTrue();
    });
});
