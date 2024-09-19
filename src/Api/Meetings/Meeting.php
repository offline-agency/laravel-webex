<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;

class Meeting extends AbstractApi
{
    public function create(
        string $title,
        string $start,
        string $end,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'agenda', 'password', 'timezone', 'recurrence', 'enabledAutoRecordMeeting', 'allowAnyUserToBeCoHost', 'enabledJoinBeforeHost', 'enableConnectAudioBeforeHost', 'joinBeforeHostMinutes', 'excludePassword', 'publicMeeting', 'reminderTime', 'sessionTypeId', 'scheduledType', 'enabledWebcastView', 'panelistPassword', 'enableAutomaticLock', 'automaticLockMinutes', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices', 'invitees', 'sendEmail', 'hostEmail', 'siteUrl', 'registration', 'integrationTags',
        ]);

        $response = $this->post('meetings', array_merge([
            'title' => $title,
            'start' => $start,
            'end' => $end,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detail(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail',
        ]);

        $response = $this->get('meetings/'.$meetingId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function list(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingNumber', 'webLink', 'roomId', 'meetingType', 'state', 'participantEmail', 'current', 'from', 'to', 'max', 'hostEmail', 'siteUrl', 'integrationTag',
        ]);

        $response = $this->get('meetings', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function listSeries(
        string $meetingSeriesId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max' , 'from' , 'to' , 'meetingType' , 'state' , 'isModified' , 'hasChat' , 'hasRecording' , 'hasTranscription' , 'hasCloseCaption' , 'hasPolls' , 'hasQA' , 'hostEmail'
        ]);

        $response = $this->get('meetings', array_merge([
            'meetingSeriesId' => $meetingSeriesId
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    //TODO check function patch
    public function patch(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'title', 'agenda', 'password', 'start', 'end', 'timezone', 'recurrence', 'enabledAutoRecordMeeting', 'allowAnyUserToBeCoHost', 'enabledJoinBeforeHost', 'enableConnectAudioBeforeHost', 'joinBeforeHostMinutes', 'excludePassword', 'publicMeeting', 'reminderTime', 'sessionTypeId', 'scheduledType', 'enabledWebcastView', 'panelistPassword', 'enableAutomaticLock', 'automaticLockMinutes', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices', 'sendEmail', 'hostEmail', 'siteUrl', 'registration', 'integrationTags',
        ]);

        $response = $this->put('meetings/'.$meetingId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function update(
        string $meeting_id,
        string $title,
        string $password,
        string $start,
        string $end,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'agenda', 'timezone', 'recurrence', 'enabledAutoRecordMeeting', 'allowAnyUserToBeCoHost', 'enabledJoinBeforeHost', 'enableConnectAudioBeforeHost', 'joinBeforeHostMinutes', 'excludePassword', 'publicMeeting', 'reminderTime', 'sessionTypeId', 'scheduledType', 'enabledWebcastView', 'panelistPassword', 'enableAutomaticLock', 'automaticLockMinutes', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices', 'sendEmail', 'hostEmail', 'siteUrl', 'registration', 'integrationTags',
        ]);

        $response = $this->put('meetings/'.$meeting_id, array_merge([
            'title' => $title,
            'password' => $password,
            'start' => $start,
            'end' => $end,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function destroy(
        string $meeting_id,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail',
        ]);

        $response = $this->delete('meetings/'.$meeting_id, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting deleted';
    }

    public function join(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingId', 'meetingNumber', 'webLink', 'joinDirectly', 'email', 'displayName', 'password', 'expirationMinutes', 'registrationId', 'hostEmail', 'createJoinLinkAsWebLink', 'createStartLinkAsWebLink'
        ]);

        $response = $this->post('meetings/join', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);

    }

    public function listTemplates(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'templateType', 'locale', 'isDefault', 'isStandard', 'hostEmail', 'siteUrl'
        ]);

        $response = $this->get('meetings/templates', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function detailTemplate(
        string $templateId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('meetings/templates'.$templateId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detailControlStatus(
        string $meetingId
    ) {

        $response = $this->get('meetings/controls', $meetingId);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function updateControlStatus(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'recordingStarted', 'recordingPaused', 'locked',
            ]);

        $response = $this->put('meetings/controls', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detailSessionTypes(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'siteUrl'
        ]);

        $response = $this->get('meetings/sessionTypes', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detailSessionType(
        int $sessionTypeId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'siteUrl'
        ]);

        $response = $this->get('meetings/sessionTypes/'.$sessionTypeId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detailRegistrationForm(
        int $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail'
        ]);

        $response = $this->get('meetings/'.$meetingId.'/registration', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function updateRegistrationFrom(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'autoAcceptRequest','requireFirstName','requireEmail','requireJobTitle','requireCompanyName','requireAddress1','requireAddress2','requireCity','requireState','requireZipCode','requireCountryRegion','requireWorkPhone','requireFax','maxRegisterNum','customizedQuestions','rules'
        ]);

        $response = $this->put('meetings/'.$meetingId.'/registration', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function destroyRegistrationForm(
        string $meetingId
    ) {
        $response = $this->delete('meetings/' .$meetingId. '/registration', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Registration Form deleted';
    }

    public function register(
        string $meetingId,
        string $firstName,
        string $lastName,
        string $email,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail', 'sendEmail', 'jobTitle', 'companyName', 'address1', 'address2', 'city', 'state', 'zipCode', 'countryRegion', 'workPhone', 'fax', 'customizedQuestions'
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants', array_merge([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email
            ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function batchRegister(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail', 'items'
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants/bulkInsert', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detailInformationForRegistrant(
        string $meetingId,
        string $registrantId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail'
        ]);

        $response = $this->get('meetings/'.$meetingId.'/registrants/'.$registrantId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function listRegistrants(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'hostEmail', 'current', 'email', 'registrationTimeFrom', 'registrationTimeTo'
        ]);

        $response = $this->get('meetings/'. $meetingId .'/registrants', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function queryRegistrants(
        string $meetingId,
        array $emails,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'current', 'hostEmail', 'status', 'orderType', 'orderBy'
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants/query', array_merge([
            'emails' => $emails,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function batchUpdateRegistrantsStatus(
        string $meetingId,
        string $statusOpType,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail', 'sendEmail', 'registrants'
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants/' .$statusOpType, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function destroyRegistrant(
        string $meetingId,
        string $registrantId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail'
        ]);

        $response = $this->delete('meetings/' .$meetingId. '/registrants/'. $registrantId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Registrant deleted';
    }

    public function updateSimultaneousInterpretation(
        string $meetingId,
        bool $enabled,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'interpreters'
        ]);

        $response = $this->put('meetings/'.$meetingId.'/simultaneousInterpretation', array_merge([
            'enabled' => $enabled,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function createInterpreter(
        string $meetingId,
        string $languageCode1,
        string $languageCode2,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'email', 'displayName', 'hostEmail', 'sendEmail'
        ]);

        $response = $this->post('meetings/'.$meetingId.'/interpreters', array_merge([
            'languageCode1' => $languageCode1,
            'languageCode2' => $languageCode2
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function detailInterpreter(
        string $meetingId,
        string $interpreterId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail'
        ]);

        $response = $this->get('meetings/'.$meetingId.'/interpreters/'.$interpreterId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function listInterpreters(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail'
        ]);

        $response = $this->get('meetings/'. $meetingId .'interpreters', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function updateInterpreter(
        string $meetingId,
        string $interpreterId,
        string $languageCode1,
        string $languageCode2,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'email', 'displayName', 'hostEmail', 'sendEmail'
        ]);

        $response = $this->put('meetings/'.$meetingId.'/interpreters/'.$interpreterId, array_merge([
            'languageCode1' => $languageCode1,
            'languageCode2' => $languageCode2
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function destroyInterpreter(
        string $meetingId,
        string $interpreterId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail'
        ]);

        $response = $this->delete('meetings/' .$meetingId. '/interpreters/'.$interpreterId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Interpreter deleted';
    }

    public function listBreakoutSessions(
        string $meetingId
    ) {

        $response = $this->get('meetings/'. $meetingId .'breakoutSessions', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function updateBreakoutSessions(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail', 'items'
        ]);

        $response = $this->put('meetings/'.$meetingId.'/breakoutSessions', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function destroyBreakoutSessions(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'sendEmail'
        ]);

        $response = $this->delete('meetings/' .$meetingId. '/breakoutSessions', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Breakout Sessions deleted';
    }

    public function detailSurvey(
        string $meetingId
    ) {

        $response = $this->get('meetings/'.$meetingId.'/survey', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function listSurveyResults(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingStartTimeFrom', 'meetingStartTimeTo', 'max'
        ]);

        $response = $this->get('meetings/'. $meetingId .'/surveyResults', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function detailSurveyLinks(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'meetingStartTimeFrom', 'meetingStartTimeTo', 'emails'
        ]);

        $response = $this->get('meetings/'.$meetingId.'/surveyLinks', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function createInvitationSources(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'personId', 'items'
        ]);

        $response = $this->post('meetings/'.$meetingId.'/invitationSources', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function listInvitationSources(
        string $meetingId
    ) {

        $response = $this->get('meetings/'. $meetingId .'/invitationSources', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function listMeetingTrackingCodes(
        string $service,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl', 'hostEmail'
        ]);

        $response = $this->put('meetings/trackingCodes', array_merge([
            'service' => $service
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function reassignToNewHost(
        string $hostEmail,
        array $meetingIds
    ) {
        $response = $this->post('meetings/reassignHost', [
            'hostEmail' => $hostEmail,
            'meetingIds' => $meetingIds
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }
}
