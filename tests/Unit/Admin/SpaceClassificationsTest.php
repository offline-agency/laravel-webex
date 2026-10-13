<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\SpaceClassification as SpaceClassificationEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin SpaceClassifications', function () {
    it('lists space classifications', function () {
        Http::fake([
            'https://webexapis.com/v1/classifications*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'sc1',
                        'name' => 'Classification One',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_space_classifications()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(SpaceClassificationEntity::class);
        expect($list[0]->id)->toEqual('sc1');
    });

});
