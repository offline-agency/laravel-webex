<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingsSummaryReport as MeetingsSummaryReportEntity;

class MeetingsSummaryReport extends AbstractApi
{
    public function listUsageReports(
        string $siteUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'serviceType', 'from', 'to', 'max',
        ]);

        $response = $this->get('meetingReports/usage', array_merge([
            'siteUrl' => $siteUrl,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingQAndA = $response->data;

        return array_map(function ($meetingQAndA) {
            return new MeetingsSummaryReportEntity($meetingQAndA);
        }, $meetingQAndA->items);
    }

    public function listAttendeeReports(
        string $siteUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingId', 'meetingNumber', 'meetingTitle', 'from', 'to', 'max',
        ]);

        $response = $this->get('meetingReports/attendees', array_merge([
            'siteUrl' => $siteUrl,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingQAndA = $response->data;

        return array_map(function ($meetingQAndA) {
            return new MeetingsSummaryReportEntity($meetingQAndA);
        }, $meetingQAndA->items);
    }
}
