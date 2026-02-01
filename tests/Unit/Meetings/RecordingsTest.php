<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Recordings as RecordingsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Recordings', function () {
    it('lists recordings', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'rec1', 'topic' => 'Meeting 1', 'hostEmail' => 'host@example.com'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->recordings()->listRecordings();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RecordingsEntity::class);
    });

    it('returns error on recordings list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->listRecordings();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets recording detail', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response(json_encode((object) [
                'id' => 'rec1',
                'topic' => 'Meeting 1',
                'hostEmail' => 'host@example.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $recording = $laravel_webex->recordings()->detailRecording('rec1');

        expect($recording)->toBeInstanceOf(RecordingsEntity::class);
    });

    it('returns error on recording detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response(json_encode((object) [
                'message' => 'Not found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->detailRecording('rec1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('destroys recording', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->destroyRecording('rec1');

        expect($result)->toEqual('Recording deleted');
    });

    it('returns error on destroy recording failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->destroyRecording('rec1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('moves recordings into recycle bin', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/softDelete*' => Http::response(json_encode((object) [
                'id' => 'rec1',
                'status' => 'deleted',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->moveRecordingsIntoRecycleBin(['rec1']);

        expect($result)->toBeInstanceOf(RecordingsEntity::class);
    });

    it('restores recordings from recycle bin', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/restore*' => Http::response(json_encode((object) [
                'id' => 'rec1',
                'status' => 'active',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->restoreRecordingsFromRecycleBin();

        expect($result)->toBeInstanceOf(RecordingsEntity::class);
    });

    it('purges recordings from recycle bin', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/purge*' => Http::response(json_encode((object) [
                'id' => null,
                'status' => 'purged',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->purgeRecordingsFromRecycleBin();

        expect($result)->toBeInstanceOf(RecordingsEntity::class);
    });

    it('returns error on move recordings into recycle bin failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/softDelete*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->moveRecordingsIntoRecycleBin(['rec1']);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('lists recordings for admin or compliance officer', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/recordings*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'rec1', 'topic' => 'Meeting 1', 'hostEmail' => 'admin@example.com'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->recordings()->listRecordingsForAnAdminOrComplianceOfficer();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RecordingsEntity::class);
    });

    it('returns error on list recordings for admin or compliance officer failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/recordings*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->listRecordingsForAnAdminOrComplianceOfficer();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on restore recordings from recycle bin failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/restore*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->restoreRecordingsFromRecycleBin();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on purge recordings from recycle bin failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/purge*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->purgeRecordingsFromRecycleBin();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
