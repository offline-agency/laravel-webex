<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingsFakeResponse;

describe('Meetings', function () {
    it('lists meetings', function () {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meetings_list = $laravel_webex->meeting()->list();

        expect($meetings_list)->toHaveCount(2);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            expect($meeting)->toBeInstanceOf(Meeting::class);
            $single_meeting = $meeting;
        }

        expect($single_meeting->id)->toEqual('fake_id');
    });

    it('lists filtered meetings', function () {
        Http::fake([
            'meetings?state=inProgress' => Http::response(
                (new MeetingsFakeResponse)->getFilteredMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meetings_list = $laravel_webex->meeting()->list([
            'state' => 'inProgress',
        ]);

        expect($meetings_list)->toHaveCount(1);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            expect($meeting)->toBeInstanceOf(Meeting::class);
            $single_meeting = $meeting;
        }

        expect($single_meeting->id)->toEqual('fake_id');
    });

    it('returns error on meeting list failure', function () {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->list();

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_series', function () {
        Http::fake([
            'meetings?meetingSeriesId=fake_meeting_series_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListSeries()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meetings_list = $laravel_webex->meeting()->listSeries('fake_meeting_series_id');

        expect($meetings_list)->toHaveCount(2);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            expect($meeting)->toBeInstanceOf(Meeting::class);
            $single_meeting = $meeting;
        }

        expect($single_meeting->id)->toEqual('fake_id');
    });

    it('error_on_meetings_list_series', function () {
        Http::fake([
            'meetings?meetingSeriesId=fake_meeting_series_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListSeries(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listSeries('fake_meeting_series_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    /* detail */

    it('meeting_detail', function () {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id');

        expect($meeting_detail)->toBeInstanceOf(Meeting::class);
        expect($meeting_detail->id)->toEqual('fake_id');
    });

    it('filtered_meeting_detail', function () {
        Http::fake([
            'meetings/fake_id?current=0' => Http::response(
                (new MeetingsFakeResponse)->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id', [
            'current' => false,
        ]);

        expect($meeting_detail)->toBeInstanceOf(Meeting::class);
        expect($meeting_detail->id)->toEqual('fake_id');
    });

    /* create */

    it('meeting_create', function () {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse)->getNewMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_meeting = $laravel_webex->meeting()->create('fake_title', 'fake_start', 'fake_end', [
            'agenda' => 'fake_created_agenda',
            'enabledAutoRecordMeeting' => true,
        ]);

        expect($new_meeting)->toBeInstanceOf(Meeting::class);
        expect($new_meeting->id)->toEqual('fake_id');
        expect($new_meeting->agenda)->toEqual('fake_created_agenda');
        expect($new_meeting->enabledAutoRecordMeeting)->toBeTrue();
    });

    /* update */

    it('meeting_update', function () {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse)->getUpdatedMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_meeting = $laravel_webex->meeting()->update('fake_id', 'fake_title', 'fake_password', 'fake_start', 'fake_end', [
            'agenda' => 'fake_updated_agenda',
            'enabledAutoRecordMeeting' => false,
        ]);

        expect($updated_meeting)->toBeInstanceOf(Meeting::class);
        expect($updated_meeting->id)->toEqual('fake_id');
        expect($updated_meeting->agenda)->toEqual('fake_updated_agenda');
        expect($updated_meeting->enabledAutoRecordMeeting)->toBeFalse();
    });

    /* delete */

    it('meeting_delete', function () {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse)->getDeleteMeetingFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroy('fake_id');

        expect($delete_response)->toEqual('Meeting deleted');
    });

    it('meeting_delete_without_mail', function () {
        Http::fake([
            'meetings/fake_id?sendEmail=0' => Http::response(
                (new MeetingsFakeResponse)->getDeleteMeetingFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroy('fake_id', [
            'sendEmail' => false,
        ]);

        expect($delete_response)->toEqual('Meeting deleted');
    });

    it('error_on_meeting_delete', function () {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroy('fake_id');

        expect($delete_response)->toBeInstanceOf(Error::class);
        expect($delete_response->message)->toEqual('fake_message');
        expect($delete_response->errors)->toBeArray();
        expect($delete_response->trackingId)->toEqual('fake_trackingId');
    });

    it('meeting_join', function () {
        Http::fake([
            'meetings/join' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeJoinDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_detail = $laravel_webex->meeting()->join();

        expect($meeting_detail)->toBeInstanceOf(Meeting::class);
    });

    it('error_on_meeting_join', function () {
        Http::fake([
            'meetings/join' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeJoin(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->join();

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_templates', function () {
        Http::fake([
            'meetings/templates' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListTemplates()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meetings_list = $laravel_webex->meeting()->listTemplates();

        expect($meetings_list)->toHaveCount(2);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            expect($meeting)->toBeInstanceOf(Meeting::class);
            $single_meeting = $meeting;
        }

        expect($single_meeting->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_templates', function () {
        Http::fake([
            'meetings/templates' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListTemplates(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listTemplates();

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_template', function () {
        Http::fake([
            'meetings/templates/fake_template_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailTemplate()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $meetings_template_detail = $laravel_webex->meeting()->detailTemplate('fake_template_id');

        expect($meetings_template_detail)->toBeInstanceOf(Meeting::class);
        expect($meetings_template_detail->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_template', function () {
        Http::fake([
            'meetings/templates/fake_template_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailTemplate(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailTemplate('fake_template_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_control_status', function () {
        Http::fake([
            'meetings/controls?meetingId=fake_meeting_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailControlStatus()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $control_status_detail = $laravel_webex->meeting()->detailControlStatus('fake_meeting_id');

        expect($control_status_detail)->toBeInstanceOf(Meeting::class);
        expect($control_status_detail->id)->toEqual('fake_id');
    });

    it('error_on_meeting_control_status', function () {
        Http::fake([
            'meetings/controls?meetingId=fake_meeting_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeControlStatus(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailControlStatus('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_update_control_status', function () {
        Http::fake([
            'meetings/controls' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeUpdateControlStatus()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_control_status = $laravel_webex->meeting()->updateControlStatus('fake_meeting_id');

        expect($updated_control_status)->toBeInstanceOf(Meeting::class);
        expect($updated_control_status->id)->toEqual('fake_id');
    });

    it('error_on_meeting_update_control_status', function () {
        Http::fake([
            'meetings/controls' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeUpdateControlStatus(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->updateControlStatus('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_session_types', function () {
        Http::fake([
            'meetings/sessionTypes' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListSessionTypes()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $session_types = $laravel_webex->meeting()->listSessionTypes();

        expect($session_types)->toHaveCount(2);

        $single_session_type = null;
        foreach ($session_types as $session_type) {
            expect($session_type)->toBeInstanceOf(Meeting::class);
            $single_session_type = $session_type;
        }

        expect($single_session_type->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_session_types', function () {
        Http::fake([
            'meetings/sessionTypes' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListSessionTypes(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listSessionTypes();

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_session_type', function () {
        Http::fake([
            'meetings/sessionTypes/fake_session_type_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailSessionType()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $session_type_detail = $laravel_webex->meeting()->detailSessionType('fake_session_type_id');

        expect($session_type_detail)->toBeInstanceOf(Meeting::class);
        expect($session_type_detail->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_session_types', function () {
        Http::fake([
            'meetings/sessionTypes/fake_session_type_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailSessionTypes(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailSessionType('fake_session_type_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_registration_form', function () {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailRegistrationForm()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $registration_form_detail = $laravel_webex->meeting()->detailRegistrationForm('fake_meeting_id');

        expect($registration_form_detail)->toBeInstanceOf(Meeting::class);
        expect($registration_form_detail->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_registration_form', function () {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailRegistrationForm(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailRegistrationForm('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_update_registration_form', function () {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeUpdateRegistrationForm()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_registration_form = $laravel_webex->meeting()->updateRegistrationForm('fake_meeting_id');

        expect($updated_registration_form)->toBeInstanceOf(Meeting::class);
        expect($updated_registration_form->id)->toEqual('fake_id');
    });

    it('error_on_meeting_update_registration_form', function () {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeUpdateRegistrationForm(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->updateRegistrationForm('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_destroy_registration_form', function () {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDestroyRegistrationForm()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroyRegistrationForm('fake_meeting_id');

        expect($delete_response)->toEqual('Meeting Registration Form deleted');
    });

    it('error_on_meeting_destroy_registration_form', function () {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDestroyRegistrationForm(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->destroyRegistrationForm('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_register', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeRegister()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $register = $laravel_webex->meeting()->register('fake_meeting_id', 'fake_first_name', 'fake_last_name', 'fake_email');

        expect($register)->toBeInstanceOf(Meeting::class);
        expect($register->id)->toEqual('fake_id');
    });

    it('error_on_meeting_register', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeRegister(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->register('fake_meeting_id', 'fake_first_name', 'fake_last_name', 'fake_email');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_batch_register', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/bulkInsert' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeBatchRegister()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $batch_register = $laravel_webex->meeting()->batchRegister('fake_meeting_id');

        expect($batch_register)->toBeInstanceOf(Meeting::class);
        expect($batch_register->id)->toEqual('fake_id');
    });

    it('error_on_meeting_batch_register', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/bulkInsert' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeBatchRegister(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->batchRegister('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_information_for_registrant', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailInformationForRegistrant()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $information_for_registrant_details = $laravel_webex->meeting()->detailInformationForRegistrant('fake_meeting_id', 'fake_registrant_id');

        expect($information_for_registrant_details)->toBeInstanceOf(Meeting::class);
        expect($information_for_registrant_details->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_information_for_registrant', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailInformationForRegistrant(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailInformationForRegistrant('fake_meeting_id', 'fake_registrant_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_registrants', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListRegistrants()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $registrants = $laravel_webex->meeting()->listRegistrants('fake_meeting_id');

        expect($registrants)->toHaveCount(2);

        $single_registrant = null;
        foreach ($registrants as $registrant) {
            expect($registrant)->toBeInstanceOf(Meeting::class);
            $single_registrant = $registrant;
        }

        expect($single_registrant->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_registrants', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListRegistrants(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listRegistrants('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_query_registrants', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/query' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeQueryRegistrants()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $query_registrants = $laravel_webex->meeting()->queryRegistrants('fake_meeting_id', ['fake_email_1', 'fake_email_2']);

        expect($query_registrants)->toBeInstanceOf(Meeting::class);
        expect($query_registrants->id)->toEqual('fake_id');
    });

    it('error_on_meeting_query_registrants', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/query' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeQueryRegistrants(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->queryRegistrants('fake_meeting_id', ['fake_email_1', 'fake_email_2']);

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_batch_update_registrants_status', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_status_op_type' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeUpdateRegistrantsStatus()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_registrants_status = $laravel_webex->meeting()->batchUpdateRegistrantsStatus('fake_meeting_id', 'fake_status_op_type');

        expect($updated_registrants_status)->toBeInstanceOf(Meeting::class);
        expect($updated_registrants_status->id)->toEqual('fake_id');
    });

    it('error_on_meeting_batch_update_registrants_status', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_status_op_type' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeUpdateRegistrantsStatus(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->batchUpdateRegistrantsStatus('fake_meeting_id', 'fake_status_op_type');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_destroy_registrant', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDestroyRegistrant()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroyRegistrant('fake_meeting_id', 'fake_registrant_id');

        expect($delete_response)->toEqual('Meeting Registrant deleted');
    });

    it('error_on_meeting_destroy_registrant', function () {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDestroyRegistrant(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->destroyRegistrant('fake_meeting_id', 'fake_registrant_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_update_simultaneous_interpretation', function () {
        Http::fake([
            'meetings/fake_meeting_id/simultaneousInterpretation' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeUpdateSimultaneousInterpretation()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_simultaneous_interpretation = $laravel_webex->meeting()->updateSimultaneousInterpretation('fake_meeting_id', true);

        expect($updated_simultaneous_interpretation)->toBeInstanceOf(Meeting::class);
        expect($updated_simultaneous_interpretation->id)->toEqual('fake_id');
    });

    it('error_on_meeting_update_simultaneous_interpretation', function () {
        Http::fake([
            'meetings/fake_meeting_id/simultaneousInterpretation' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeUpdateSimultaneousInterpretation(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->updateSimultaneousInterpretation('fake_meeting_id', true);

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_create_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeCreateInterpreter()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_interpreter_details = $laravel_webex->meeting()->createInterpreter('fake_meeting_id', 'fake_language_code_1', 'fake_language_code_2');

        expect($new_interpreter_details)->toBeInstanceOf(Meeting::class);
        expect($new_interpreter_details->id)->toEqual('fake_id');
    });

    it('error_on_meeting_create_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeCreateInterpreter(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->createInterpreter('fake_meeting_id', 'fake_language_code_1', 'fake_language_code_2');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailInterpreter()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $interpreter_details = $laravel_webex->meeting()->detailInterpreter('fake_meeting_id', 'fake_interpreter_id');

        expect($interpreter_details)->toBeInstanceOf(Meeting::class);
        expect($interpreter_details->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailInterpreter(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailInterpreter('fake_meeting_id', 'fake_interpreter_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_interpreters', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListInterpreters()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $interpreters = $laravel_webex->meeting()->listInterpreters('fake_meeting_id');

        expect($interpreters)->toHaveCount(2);

        $single_interpreter = null;
        foreach ($interpreters as $interpreter) {
            expect($interpreter)->toBeInstanceOf(Meeting::class);
            $single_interpreter = $interpreter;
        }

        expect($single_interpreter->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_interpreters', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListInterpreters(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listInterpreters('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_update_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeUpdateInterpreters()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_interpreter = $laravel_webex->meeting()->updateInterpreter('fake_meeting_id', 'fake_interpreter_id', 'fake_language_code_1', 'fake_language_code_2');

        expect($updated_interpreter)->toBeInstanceOf(Meeting::class);
        expect($updated_interpreter->id)->toEqual('fake_id');
    });

    it('error_on_meeting_update_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeUpdateInterpreters(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->updateInterpreter('fake_meeting_id', 'fake_interpreter_id', 'fake_language_code_1', 'fake_language_code_2');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_destroy_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDestroyInterpreter()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroyInterpreter('fake_meeting_id', 'fake_interpreter_id');

        expect($delete_response)->toEqual('Meeting Interpreter deleted');
    });

    it('error_on_meeting_destroy_interpreter', function () {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDestroyInterpreter(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->destroyInterpreter('fake_meeting_id', 'fake_interpreter_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_breakout_sessions', function () {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListBreakoutSessions()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $breakout_sessions = $laravel_webex->meeting()->listBreakoutSessions('fake_meeting_id');

        expect($breakout_sessions)->toHaveCount(2);

        $single_breakout_session = null;
        foreach ($breakout_sessions as $breakout_session) {
            expect($breakout_session)->toBeInstanceOf(Meeting::class);
            $single_breakout_session = $breakout_session;
        }

        expect($single_breakout_session->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_breakout_sessions', function () {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListBreakoutSession(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listBreakoutSessions('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_update_breakout_sessions', function () {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeUpdateBreakoutSession()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_breakout_session = $laravel_webex->meeting()->updateBreakoutSessions('fake_meeting_id');

        expect($updated_breakout_session)->toBeInstanceOf(Meeting::class);
        expect($updated_breakout_session->id)->toEqual('fake_id');
    });

    it('error_on_meeting_update_breakout_sessions', function () {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeUpdateBreakoutSession(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->updateBreakoutSessions('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_destroy_breakout_sessions', function () {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDestroyBreakoutSession()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting()->destroyBreakoutSessions('fake_meeting_id');

        expect($delete_response)->toEqual('Meeting Breakout Sessions deleted');
    });

    it('error_on_meeting_destroy_breakout_sessions', function () {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDestroyBreakoutSessions(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->destroyBreakoutSessions('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_survey', function () {
        Http::fake([
            'meetings/fake_meeting_id/survey' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailSurvey()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $survey_details = $laravel_webex->meeting()->detailSurvey('fake_meeting_id');

        expect($survey_details)->toBeInstanceOf(Meeting::class);
        expect($survey_details->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_survey', function () {
        Http::fake([
            'meetings/fake_meeting_id/survey' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailSurvey(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailSurvey('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_survey_results', function () {
        Http::fake([
            'meetings/fake_meeting_id/surveyResults' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListSurveyResults()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $survey_results = $laravel_webex->meeting()->listSurveyResults('fake_meeting_id');

        expect($survey_results)->toHaveCount(2);

        $single_survey_result = null;
        foreach ($survey_results as $survey_result) {
            expect($survey_result)->toBeInstanceOf(Meeting::class);
            $single_survey_result = $survey_result;
        }

        expect($single_survey_result->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_survey_results', function () {
        Http::fake([
            'meetings/fake_meeting_id/surveyResults' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListSurveyResults(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listSurveyResults('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_detail_survey_links', function () {
        Http::fake([
            'meetings/fake_meeting_id/surveyLinks' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeDetailSurveyLinks()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $survey_links_details = $laravel_webex->meeting()->detailSurveyLinks('fake_meeting_id');

        expect($survey_links_details)->toBeInstanceOf(Meeting::class);
        expect($survey_links_details->id)->toEqual('fake_id');
    });

    it('error_on_meeting_detail_survey_links', function () {
        Http::fake([
            'meetings/fake_meeting_id/surveyLinks' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeDetailSurveyLinks(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->detailSurveyLinks('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_create_invitation_sources', function () {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeCreateInvitationSources()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_invitation_sources_details = $laravel_webex->meeting()->createInvitationSources('fake_meeting_id');

        expect($new_invitation_sources_details)->toBeInstanceOf(Meeting::class);
        expect($new_invitation_sources_details->id)->toEqual('fake_id');
    });

    it('error_on_meeting_create_invitation_sources', function () {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeCreateInvitationSources(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->createInvitationSources('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_invitation_sources', function () {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListInvitationSources()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $invitation_sources = $laravel_webex->meeting()->listInvitationSources('fake_meeting_id');

        expect($invitation_sources)->toHaveCount(2);

        $single_invitation_source = null;
        foreach ($invitation_sources as $invitation_source) {
            expect($invitation_source)->toBeInstanceOf(Meeting::class);
            $single_invitation_source = $invitation_source;
        }

        expect($single_invitation_source->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_invitation_sources', function () {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListInvitationSources(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listInvitationSources('fake_meeting_id');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_list_tracking_codes', function () {
        Http::fake([
            'meetings/trackingCodes' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeListTrackingCodes()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $tracking_codes = $laravel_webex->meeting()->listTrackingCodes('fake_service');

        expect($tracking_codes)->toHaveCount(2);

        $single_tracking_code = null;
        foreach ($tracking_codes as $tracking_code) {
            expect($tracking_code);
            $single_tracking_code = $tracking_code;
        }

        expect($single_tracking_code->id)->toEqual('fake_id');
    });

    it('error_on_meeting_list_tracking_codes', function () {
        Http::fake([
            'meetings/trackingCodes' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeListTrackingCodes(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->listTrackingCodes('fake_service');

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });

    it('meetings_reassign_to_new_host', function () {
        Http::fake([
            'meetings/reassignHost' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeReassignToNewHost()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $reassign_to_new_host = $laravel_webex->meeting()->reassignToNewHost('fake_host_email', ['fake_meeting_id_1', 'fake_meeting_id_2']);

        expect($reassign_to_new_host)->toBeInstanceOf(Meeting::class);
        expect($reassign_to_new_host->id)->toEqual('fake_id');
    });

    it('error_on_meeting_reassign_to_new_host', function () {
        Http::fake([
            'meetings/reassignHost' => Http::response(
                (new MeetingsFakeResponse)->getErrorOnMeetingsFakeReassignToNewHost(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $error_meeting = $laravel_webex->meeting()->reassignToNewHost('fake_host_email', ['fake_meeting_id_1', 'fake_meeting_id_2']);

        expect($error_meeting)->toBeInstanceOf(Error::class);
        expect($error_meeting->message)->toEqual('fake_message');
        expect($error_meeting->errors)->toBeArray();
        expect($error_meeting->trackingId)->toEqual('fake_trackingId');
    });
});
