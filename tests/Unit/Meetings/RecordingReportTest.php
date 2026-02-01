<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\RecordingReport as RecordingReportEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('RecordingReport', function () {
    it('lists recording audit report summaries', function () {
        Http::fake([
            'https://webexapis.com/v1/recordingReport/accessSummary*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'r1', 'recordingId' => 'rec1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->recording_report()->listRecordingAuditReportSummaries();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RecordingReportEntity::class);
    });

    it('returns error on list recording audit report failure', function () {
        Http::fake([
            'https://webexapis.com/v1/recordingReport/accessSummary*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->recording_report()->listRecordingAuditReportSummaries();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets record audit report detail', function () {
        Http::fake([
            'https://webexapis.com/v1/recordingReport/accessDetail*' => Http::response(json_encode((object) [
                'recordingId' => 'rec1',
                'accessLogs' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->recording_report()->detailRecordAuditReport('rec1');

        expect($result)->toBeInstanceOf(RecordingReportEntity::class);
    });

    it('lists archive summaries', function () {
        Http::fake([
            'https://webexapis.com/v1/recordingReport/meetingArchiveSummaries*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'a1', 'archiveId' => 'arch1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->recording_report()->listArchiveSummaries();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RecordingReportEntity::class);
    });

    it('gets archive detail', function () {
        Http::fake([
            'https://webexapis.com/v1/recordingReport/meetingArchives/arch1*' => Http::response(json_encode((object) [
                'id' => 'arch1',
                'meetingId' => 'm1',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->recording_report()->detailArchive('arch1');

        expect($result)->toBeInstanceOf(RecordingReportEntity::class);
    });
});
