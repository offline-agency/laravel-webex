<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingsSummaryReport as MeetingsSummaryReportEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingsSummaryReport', function () {
    it('lists usage reports', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingReports/usage*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'r1', 'siteUrl' => 'https://example.webex.com'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meetings_summary_report()->listUsageReports('https://example.webex.com');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingsSummaryReportEntity::class);
    });

    it('returns error on list usage reports failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingReports/usage*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meetings_summary_report()->listUsageReports('https://example.webex.com');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('lists attendee reports', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingReports/attendees*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'a1', 'meetingId' => 'm1'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meetings_summary_report()->listAttendeeReports('https://example.webex.com');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingsSummaryReportEntity::class);
    });
});
