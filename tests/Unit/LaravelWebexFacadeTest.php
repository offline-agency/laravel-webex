<?php

use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\LaravelWebexFacade;

describe('LaravelWebexFacade', function () {
    it('loads facade alias', function () {
        expect(class_exists('LaravelWebex'))->toBeTrue();
        expect(is_a('LaravelWebex', LaravelWebexFacade::class, true))->toBeTrue();
    });

    it('resolves the laravel-webex binding as facade root', function () {
        expect(LaravelWebexFacade::getFacadeRoot())->toBeInstanceOf(LaravelWebex::class);
    });
});
