<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\AdminAuditEvent as AdminAuditEventEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('AdminAuditEvents', function () {
    it('lists admin audit events', function () {
        Http::fake([
            'https://webexapis.com/v1/adminAuditEvents*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'ev1',
                        'actorId' => 'u1',
                        'event' => 'user.login',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_audit_events()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(AdminAuditEventEntity::class);
        expect($list[0]->id)->toEqual('ev1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/adminAuditEvents*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_audit_events()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
