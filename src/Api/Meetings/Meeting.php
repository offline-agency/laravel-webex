<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;

class Meeting extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
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

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detail(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail',
        ]);

        $response = $this->get('meetings/'.$meetingId, $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
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

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listSeries(
        string $meetingSeriesId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'meetingType', 'state', 'isModified', 'hasChat', 'hasRecording', 'hasTranscription', 'hasCloseCaption', 'hasPolls', 'hasQA', 'hostEmail',
        ]);

        $response = $this->get('meetings', array_merge([
            'meetingSeriesId' => $meetingSeriesId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
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

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return string|Error
     */
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

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function join(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingId', 'meetingNumber', 'webLink', 'joinDirectly', 'email', 'displayName', 'password', 'expirationMinutes', 'registrationId', 'hostEmail', 'createJoinLinkAsWebLink', 'createStartLinkAsWebLink',
        ]);

        $response = $this->post('meetings/join', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listTemplates(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'templateType', 'locale', 'isDefault', 'isStandard', 'hostEmail', 'siteUrl',
        ]);

        $response = $this->get('meetings/templates', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detailTemplate(
        string $templateId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('meetings/templates/'.$templateId, $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @return MeetingsEntity|Error
     */
    public function detailControlStatus(
        string $meetingId
    ) {
        $response = $this->get('meetings/controls', [
            'meetingId' => $meetingId,
        ]);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
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

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listSessionTypes(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'siteUrl',
        ]);

        $response = $this->get('meetings/sessionTypes', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detailSessionType(
        string $sessionTypeId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'siteUrl',
        ]);

        $response = $this->get('meetings/sessionTypes/'.$sessionTypeId, $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detailRegistrationForm(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail',
        ]);

        $response = $this->get('meetings/'.$meetingId.'/registration', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function updateRegistrationForm(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'autoAcceptRequest', 'requireFirstName', 'requireEmail', 'requireJobTitle', 'requireCompanyName', 'requireAddress1', 'requireAddress2', 'requireCity', 'requireState', 'requireZipCode', 'requireCountryRegion', 'requireWorkPhone', 'requireFax', 'maxRegisterNum', 'customizedQuestions', 'rules',
        ]);

        $response = $this->put('meetings/'.$meetingId.'/registration', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @return string|Error
     */
    public function destroyRegistrationForm(
        string $meetingId
    ) {
        $response = $this->delete('meetings/'.$meetingId.'/registration', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Registration Form deleted';
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function register(
        string $meetingId,
        string $firstName,
        string $lastName,
        string $email,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail', 'sendEmail', 'jobTitle', 'companyName', 'address1', 'address2', 'city', 'state', 'zipCode', 'countryRegion', 'workPhone', 'fax', 'customizedQuestions',
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants', array_merge([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ], $additional_data));

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function batchRegister(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail', 'items',
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants/bulkInsert', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detailInformationForRegistrant(
        string $meetingId,
        string $registrantId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail',
        ]);

        $response = $this->get('meetings/'.$meetingId.'/registrants/'.$registrantId, $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listRegistrants(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'hostEmail', 'current', 'email', 'registrationTimeFrom', 'registrationTimeTo',
        ]);

        $response = $this->get('meetings/'.$meetingId.'/registrants', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  list<string>  $emails
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function queryRegistrants(
        string $meetingId,
        array $emails,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'current', 'hostEmail', 'status', 'orderType', 'orderBy',
        ]);

        $response = $this->post('meetings/'.$meetingId.'/registrants/query', array_merge([
            'emails' => $emails,
        ], $additional_data));

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function batchUpdateRegistrantsStatus(
        string $meetingId,
        string $statusOpType,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail', 'sendEmail', 'registrants',
        ]);

        if (! in_array($statusOpType, ['approve', 'reject', 'cancel', 'bulkDelete'], true)) {
            return new Error(['message' => "Unsupported registrant status operation '{$statusOpType}': use approve, reject, cancel or bulkDelete."]);
        }

        $response = $this->post('meetings/'.$meetingId.'/registrants/'.$statusOpType, $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return string|Error
     */
    public function destroyRegistrant(
        string $meetingId,
        string $registrantId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail',
        ]);

        $response = $this->delete('meetings/'.$meetingId.'/registrants/'.$registrantId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Registrant deleted';
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function updateSimultaneousInterpretation(
        string $meetingId,
        bool $enabled,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'interpreters',
        ]);

        $response = $this->put('meetings/'.$meetingId.'/simultaneousInterpretation', array_merge([
            'enabled' => $enabled,
        ], $additional_data));

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function createInterpreter(
        string $meetingId,
        string $languageCode1,
        string $languageCode2,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'email', 'displayName', 'hostEmail', 'sendEmail',
        ]);

        $response = $this->post('meetings/'.$meetingId.'/interpreters', array_merge([
            'languageCode1' => $languageCode1,
            'languageCode2' => $languageCode2,
        ], $additional_data));

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detailInterpreter(
        string $meetingId,
        string $interpreterId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('meetings/'.$meetingId.'/interpreters/'.$interpreterId, $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listInterpreters(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('meetings/'.$meetingId.'/interpreters', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function updateInterpreter(
        string $meetingId,
        string $interpreterId,
        string $languageCode1,
        string $languageCode2,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'email', 'displayName', 'hostEmail', 'sendEmail',
        ]);

        $response = $this->put('meetings/'.$meetingId.'/interpreters/'.$interpreterId, array_merge([
            'languageCode1' => $languageCode1,
            'languageCode2' => $languageCode2,
        ], $additional_data));

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return string|Error
     */
    public function destroyInterpreter(
        string $meetingId,
        string $interpreterId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail',
        ]);

        $response = $this->delete('meetings/'.$meetingId.'/interpreters/'.$interpreterId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Interpreter deleted';
    }

    /**
     * @return MeetingsEntity[]|Error
     */
    public function listBreakoutSessions(
        string $meetingId
    ) {
        $response = $this->get('meetings/'.$meetingId.'/breakoutSessions', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function updateBreakoutSessions(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail', 'items',
        ]);

        $response = $this->put('meetings/'.$meetingId.'/breakoutSessions', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return string|Error
     */
    public function destroyBreakoutSessions(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'sendEmail',
        ]);

        $response = $this->delete('meetings/'.$meetingId.'/breakoutSessions', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Breakout Sessions deleted';
    }

    /**
     * @return MeetingsEntity|Error
     */
    public function detailSurvey(
        string $meetingId
    ) {
        $response = $this->get('meetings/'.$meetingId.'/survey', []);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listSurveyResults(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingStartTimeFrom', 'meetingStartTimeTo', 'max',
        ]);

        $response = $this->get('meetings/'.$meetingId.'/surveyResults', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function detailSurveyLinks(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'meetingStartTimeFrom', 'meetingStartTimeTo', 'emails',
        ]);

        $response = $this->post('meetings/'.$meetingId.'/surveyLinks', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function createInvitationSources(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'personId', 'items',
        ]);

        $response = $this->post('meetings/'.$meetingId.'/invitationSources', $additional_data);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @return MeetingsEntity[]|Error
     */
    public function listInvitationSources(
        string $meetingId
    ) {
        $response = $this->get('meetings/'.$meetingId.'/invitationSources', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity[]|Error
     */
    public function listTrackingCodes(
        string $service,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl', 'hostEmail',
        ]);

        $response = $this->get('meetings/trackingCodes', array_merge([
            'service' => $service,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $items);
    }

    /**
     * @param  list<string>  $meetingIds
     * @return MeetingsEntity|Error
     */
    public function reassignToNewHost(
        string $hostEmail,
        array $meetingIds
    ) {
        $response = $this->post('meetings/reassignHost', [
            'hostEmail' => $hostEmail,
            'meetingIds' => $meetingIds,
        ]);

        return $this->entity($response, MeetingsEntity::class);
    }
}
