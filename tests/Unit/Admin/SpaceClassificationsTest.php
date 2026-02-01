<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\SpaceClassification as SpaceClassificationEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin SpaceClassifications', function () {
    it('lists space classifications', function () {
        Http::fake([
            'https://webexapis.com/v1/spaceClassifications*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'sc1',
                        'name' => 'Classification One',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_space_classifications()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(SpaceClassificationEntity::class);
        expect($list[0]->id)->toEqual('sc1');
    });

    it('gets space classification detail', function () {
        Http::fake([
            'https://webexapis.com/v1/spaceClassifications/sc1*' => Http::response(json_encode((object) [
                'id' => 'sc1',
                'name' => 'Classification One',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $classification = $laravel_webex->admin_space_classifications()->detail('sc1');

        expect($classification)->toBeInstanceOf(SpaceClassificationEntity::class);
        expect($classification->id)->toEqual('sc1');
    });

    it('creates space classification', function () {
        Http::fake([
            'https://webexapis.com/v1/spaceClassifications' => Http::response(json_encode((object) [
                'id' => 'sc2',
                'name' => 'New Classification',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $classification = $laravel_webex->admin_space_classifications()->create(['name' => 'New Classification', 'orgId' => 'org1']);

        expect($classification)->toBeInstanceOf(SpaceClassificationEntity::class);
        expect($classification->id)->toEqual('sc2');
    });

    it('destroys space classification', function () {
        Http::fake([
            'https://webexapis.com/v1/spaceClassifications/sc1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_space_classifications()->destroy('sc1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/spaceClassifications*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_space_classifications()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
