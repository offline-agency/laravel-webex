<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\BroadWorksEnterprise as BroadWorksEnterpriseEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Calling BroadWorksEnterprises', function () {
    it('lists broadworks enterprises', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworks/enterprises*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'ent1',
                        'name' => 'Enterprise One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->broadworks_enterprises()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(BroadWorksEnterpriseEntity::class);
        expect($list[0]->id)->toEqual('ent1');
    });

});
