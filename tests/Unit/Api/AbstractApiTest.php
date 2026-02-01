<?php

use Illuminate\Support\Facades\Event;
use Offlineagency\LaravelWebex\Api\Admin\Licenses;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('AbstractApi helpers', function () {
    beforeEach(function () {
        Event::fake([AuthenticationRequested::class, SuccessfulAuthentication::class]);
    });

    it('data() returns only allowed keys from array', function () {
        $api = (new LaravelWebex())->admin_licenses();

        $result = $api->data(['a' => 1, 'b' => 2, 'c' => 3], ['a', 'c']);

        expect($result)->toBe(['a' => 1, 'c' => 3]);
    });

    it('data() omits keys not in fields', function () {
        $api = (new LaravelWebex())->admin_licenses();

        $result = $api->data(['x' => 1, 'y' => 2], ['x']);

        expect($result)->toBe(['x' => 1]);
        expect($result)->not->toHaveKey('y');
    });

    it('data() returns empty array when fields is empty', function () {
        $api = (new LaravelWebex())->admin_licenses();

        $result = $api->data(['a' => 1, 'b' => 2], []);

        expect($result)->toBe([]);
    });

    it('value() returns value when key exists', function () {
        $api = (new LaravelWebex())->admin_licenses();

        $result = $api->value(['x' => 1], 'x');

        expect($result)->toBe(1);
    });

    it('value() returns null when key is missing and no default', function () {
        $api = (new LaravelWebex())->admin_licenses();

        $result = $api->value(['x' => 1], 'y');

        expect($result)->toBeNull();
    });

    it('value() returns default when key is missing', function () {
        $api = (new LaravelWebex())->admin_licenses();

        $result = $api->value([], 'y', 'default');

        expect($result)->toBe('default');
    });
});
