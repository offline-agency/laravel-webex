<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\RecordingReport as RecordingReportEntity;

class RecordingReport extends AbstractApi
{
    public function listRecordingAuditReportSummaries(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'hostEmail', 'siteUrl',
        ]);

        $response = $this->get('recordingReport/accessSummary', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $recordingReports = $response->data;

        return array_map(function ($recordingReport) {
            return new RecordingReportEntity($recordingReport);
        }, $recordingReports->items);
    }

    public function detailRecordAuditReport(
        string $recordingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'max',
        ]);

        $response = $this->get('recordingReport/accessDetail', array_merge([
            'recordingId' => $recordingId,
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        return new RecordingReportEntity($response->data);
    }

    public function listArchiveSummaries(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'siteUrl',
        ]);

        $response = $this->get('recordingReport/meetingArchiveSummaries', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $recordingReports = $response->data;

        return array_map(function ($recordingReport) {
            return new RecordingReportEntity($recordingReport);
        }, $recordingReports->items);
    }

    public function detailArchive(
        string $archiveId
    ) {
        $response = $this->get('recordingReport/meetingArchives/'.$archiveId, []);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new RecordingReportEntity($response->data);
    }
}
