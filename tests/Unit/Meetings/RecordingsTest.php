<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\Recordings as RecordingsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Recordings', function () {
    it('lists recordings', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'rec1', 'topic' => 'Meeting 1', 'hostEmail' => 'host@example.com'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->recordings()->listRecordings();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RecordingsEntity::class);
    });

    it('gets recording detail', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response(json_encode((object) [
                'id' => 'rec1',
                'topic' => 'Meeting 1',
                'hostEmail' => 'host@example.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $recording = $laravel_webex->recordings()->detailRecording('rec1');

        expect($recording)->toBeInstanceOf(RecordingsEntity::class);
    });

    it('destroys recording', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->destroyRecording('rec1');

        expect($result)->toEqual('Recording deleted');
    });

    it('moves recordings into recycle bin', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/softDelete*' => Http::response(json_encode((object) [
                'id' => 'rec1',
                'status' => 'deleted',
            ], JSON_THROW_ON_ERROR)),
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
            ], JSON_THROW_ON_ERROR)),
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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->purgeRecordingsFromRecycleBin();

        expect($result)->toBeInstanceOf(RecordingsEntity::class);
    });

    it('lists recordings for admin or compliance officer', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/recordings*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'rec1', 'topic' => 'Meeting 1', 'hostEmail' => 'admin@example.com'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->recordings()->listRecordingsForAnAdminOrComplianceOfficer();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RecordingsEntity::class);
    });

});
