<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Meetings;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingsTest extends TestCase
{
    /* list */

    public function test_meetings_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_list = $laravel_webex->meeting()->list();

        $this->assertCount(2, $meetings_list);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
            $single_meeting = $meeting;
        }

        $this->assertEquals('fake_id', $single_meeting->id);
    }

    public function test_filtered_meetings_list()
    {
        Http::fake([
            'meetings?state=inProgress' => Http::response(
                (new MeetingsFakeResponse())->getFilteredMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_list = $laravel_webex->meeting()->list([
            'state' => 'inProgress',
        ]);

        $this->assertCount(1, $meetings_list);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
            $single_meeting = $meeting;
        }

        $this->assertEquals('fake_id', $single_meeting->id);
    }

    public function test_error_on_meeting_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->list();

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_series()
    {
        Http::fake([
            'meetings?meetingSeriesId=fake_meeting_series_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListSeries()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_list = $laravel_webex->meeting()->listSeries('fake_meeting_series_id');

        $this->assertCount(2, $meetings_list);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
            $single_meeting = $meeting;
        }

        $this->assertEquals('fake_id', $single_meeting->id);
    }

    public function test_error_on_meetings_list_series()
    {
        Http::fake([
            'meetings?meetingSeriesId=fake_meeting_series_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListSeries(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listSeries('fake_meeting_series_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    /* detail */

    public function test_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id');

        $this->assertInstanceOf(Meeting::class, $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);
    }

    public function test_filtered_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id?current=0' => Http::response(
                (new MeetingsFakeResponse())->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id', [
            'current' => false,
        ]);

        $this->assertInstanceOf(Meeting::class, $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);
    }

    /* create */

    public function test_meeting_create()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getNewMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $new_meeting = $laravel_webex->meeting()->create('fake_title', 'fake_start', 'fake_end', [
            'agenda' => 'fake_created_agenda',
            'enabledAutoRecordMeeting' => true,
        ]);

        $this->assertInstanceOf(Meeting::class, $new_meeting);
        $this->assertEquals('fake_id', $new_meeting->id);
        $this->assertEquals('fake_created_agenda', $new_meeting->agenda);
        $this->assertEquals(true, $new_meeting->enabledAutoRecordMeeting);
    }

    /* update */

    public function test_meeting_update()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getUpdatedMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_meeting = $laravel_webex->meeting()->update('fake_id', 'fake_title', 'fake_password', 'fake_start', 'fake_end', [
            'agenda' => 'fake_updated_agenda',
            'enabledAutoRecordMeeting' => false,
        ]);

        $this->assertInstanceOf(Meeting::class, $updated_meeting);
        $this->assertEquals('fake_id', $updated_meeting->id);
        $this->assertEquals('fake_updated_agenda', $updated_meeting->agenda);
        $this->assertEquals(false, $updated_meeting->enabledAutoRecordMeeting);
    }

    /* delete */

    public function test_meeting_delete()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getDeleteMeetingFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroy('fake_id');

        $this->assertEquals('Meeting deleted', $delete_response);
    }

    public function test_meeting_delete_without_mail()
    {
        Http::fake([
            'meetings/fake_id?sendEmail=0' => Http::response(
                (new MeetingsFakeResponse())->getDeleteMeetingFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroy('fake_id', [
            'sendEmail' => false,
        ]);

        $this->assertEquals('Meeting deleted', $delete_response);
    }

    public function test_error_on_meeting_delete()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroy('fake_id');

        $this->assertInstanceOf(Error::class, $delete_response);
        $this->assertEquals('fake_message', $delete_response->message);
        $this->assertIsArray($delete_response->errors);
        $this->assertEquals('fake_trackingId', $delete_response->trackingId);
    }

    public function test_meeting_join()
    {
        Http::fake([
            'meetings/join' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeJoinDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_detail = $laravel_webex->meeting()->join();

        $this->assertInstanceOf(Meeting::class, $meeting_detail);
    }

    public function test_error_on_meeting_join()
    {
        Http::fake([
            'meetings/join' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeJoin(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->join();

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_templates()
    {
        Http::fake([
            'meetings/templates' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListTemplates()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_list = $laravel_webex->meeting()->listTemplates();

        $this->assertCount(2, $meetings_list);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
            $single_meeting = $meeting;
        }

        $this->assertEquals('fake_id', $single_meeting->id);
    }

    public function test_error_on_meeting_list_templates()
    {
        Http::fake([
            'meetings/templates' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListTemplates(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listTemplates();

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_template()
    {
        Http::fake([
            'meetings/templates/fake_template_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailTemplate()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_template_detail = $laravel_webex->meeting()->detailTemplate('fake_template_id');

        $this->assertInstanceOf(Meeting::class, $meetings_template_detail);
        $this->assertEquals('fake_id', $meetings_template_detail->id);
    }

    public function test_error_on_meeting_detail_template()
    {
        Http::fake([
            'meetings/templates/fake_template_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailTemplate(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailTemplate('fake_template_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_control_status()
    {
        Http::fake([
            'meetings/controls?meetingId=fake_meeting_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailControlStatus()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $control_status_detail = $laravel_webex->meeting()->detailControlStatus('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $control_status_detail);
        $this->assertEquals('fake_id', $control_status_detail->id);
    }

    public function test_error_on_meeting_control_status()
    {
        Http::fake([
            'meetings/controls?meetingId=fake_meeting_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeControlStatus(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailControlStatus('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_update_control_status()
    {
        Http::fake([
            'meetings/controls' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeUpdateControlStatus()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_control_status = $laravel_webex->meeting()->updateControlStatus('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $updated_control_status);
        $this->assertEquals('fake_id', $updated_control_status->id);
    }

    public function test_error_on_meeting_update_control_status()
    {
        Http::fake([
            'meetings/controls' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeUpdateControlStatus(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->updateControlStatus('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_session_types()
    {
        Http::fake([
            'meetings/sessionTypes' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListSessionTypes()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $session_types = $laravel_webex->meeting()->listSessionTypes();

        $this->assertCount(2, $session_types);

        $single_session_type = null;
        foreach ($session_types as $session_type) {
            $this->assertInstanceOf(Meeting::class, $session_type);
            $single_session_type = $session_type;
        }

        $this->assertEquals('fake_id', $single_session_type->id);
    }

    public function test_error_on_meeting_list_session_types()
    {
        Http::fake([
            'meetings/sessionTypes' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListSessionTypes(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listSessionTypes();

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_session_type()
    {
        Http::fake([
            'meetings/sessionTypes/fake_session_type_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailSessionType()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $session_type_detail = $laravel_webex->meeting()->detailSessionType('fake_session_type_id');

        $this->assertInstanceOf(Meeting::class, $session_type_detail);
        $this->assertEquals('fake_id', $session_type_detail->id);
    }

    public function test_error_on_meeting_detail_session_types()
    {
        Http::fake([
            'meetings/sessionTypes/fake_session_type_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailSessionTypes(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailSessionType('fake_session_type_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_registration_form()
    {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailRegistrationForm()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $registration_form_detail = $laravel_webex->meeting()->detailRegistrationForm('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $registration_form_detail);
        $this->assertEquals('fake_id', $registration_form_detail->id);
    }

    public function test_error_on_meeting_detail_registration_form()
    {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailRegistrationForm(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailRegistrationForm('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_update_registration_form()
    {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeUpdateRegistrationForm()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_registration_form = $laravel_webex->meeting()->updateRegistrationForm('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $updated_registration_form);
        $this->assertEquals('fake_id', $updated_registration_form->id);
    }

    public function test_error_on_meeting_update_registration_form()
    {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeUpdateRegistrationForm(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->updateRegistrationForm('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_destroy_registration_form()
    {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDestroyRegistrationForm()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroyRegistrationForm('fake_meeting_id');

        $this->assertEquals('Meeting Registration Form deleted', $delete_response);
    }

    public function test_error_on_meeting_destroy_registration_form()
    {
        Http::fake([
            'meetings/fake_meeting_id/registration' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDestroyRegistrationForm(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->destroyRegistrationForm('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_register()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeRegister()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $register = $laravel_webex->meeting()->register('fake_meeting_id', 'fake_first_name', 'fake_last_name', 'fake_email');

        $this->assertInstanceOf(Meeting::class, $register);
        $this->assertEquals('fake_id', $register->id);
    }

    public function test_error_on_meeting_register()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeRegister(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->register('fake_meeting_id', 'fake_first_name', 'fake_last_name', 'fake_email');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_batch_register()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/bulkInsert' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeBatchRegister()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $batch_register = $laravel_webex->meeting()->batchRegister('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $batch_register);
        $this->assertEquals('fake_id', $batch_register->id);
    }

    public function test_error_on_meeting_batch_register()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/bulkInsert' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeBatchRegister(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->batchRegister('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_information_for_registrant()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailInformationForRegistrant()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $information_for_registrant_details = $laravel_webex->meeting()->detailInformationForRegistrant('fake_meeting_id', 'fake_registrant_id');

        $this->assertInstanceOf(Meeting::class, $information_for_registrant_details);
        $this->assertEquals('fake_id', $information_for_registrant_details->id);
    }

    public function test_error_on_meeting_detail_information_for_registrant()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailInformationForRegistrant(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailInformationForRegistrant('fake_meeting_id', 'fake_registrant_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_registrants()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListRegistrants()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $registrants = $laravel_webex->meeting()->listRegistrants('fake_meeting_id');

        $this->assertCount(2, $registrants);

        $single_registrant = null;
        foreach ($registrants as $registrant) {
            $this->assertInstanceOf(Meeting::class, $registrant);
            $single_registrant = $registrant;
        }

        $this->assertEquals('fake_id', $single_registrant->id);
    }

    public function test_error_on_meeting_list_registrants()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListRegistrants(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listRegistrants('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_query_registrants()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/query' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeQueryRegistrants()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $query_registrants = $laravel_webex->meeting()->queryRegistrants('fake_meeting_id', ['fake_email_1', 'fake_email_2',]);

        $this->assertInstanceOf(Meeting::class, $query_registrants);
        $this->assertEquals('fake_id', $query_registrants->id);
    }

    public function test_error_on_meeting_query_registrants()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/query' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeQueryRegistrants(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->queryRegistrants('fake_meeting_id', ['fake_email_1', 'fake_email_2',]);

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_batch_update_registrants_status()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_status_op_type' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeUpdateRegistrantsStatus()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_registrants_status = $laravel_webex->meeting()->batchUpdateRegistrantsStatus('fake_meeting_id', 'fake_status_op_type');

        $this->assertInstanceOf(Meeting::class, $updated_registrants_status);
        $this->assertEquals('fake_id', $updated_registrants_status->id);
    }

    public function test_error_on_meeting_batch_update_registrants_status()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_status_op_type' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeUpdateRegistrantsStatus(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->batchUpdateRegistrantsStatus('fake_meeting_id', 'fake_status_op_type');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_destroy_registrant()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDestroyRegistrant()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroyRegistrant('fake_meeting_id', 'fake_registrant_id');

        $this->assertEquals('Meeting Registrant deleted', $delete_response);
    }

    public function test_error_on_meeting_destroy_registrant()
    {
        Http::fake([
            'meetings/fake_meeting_id/registrants/fake_registrant_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDestroyRegistrant(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->destroyRegistrant('fake_meeting_id', 'fake_registrant_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_update_simultaneous_interpretation()
    {
        Http::fake([
            'meetings/fake_meeting_id/simultaneousInterpretation' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeUpdateSimultaneousInterpretation()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_simultaneous_interpretation= $laravel_webex->meeting()->updateSimultaneousInterpretation('fake_meeting_id', 'fake_enabled');

        $this->assertInstanceOf(Meeting::class, $updated_simultaneous_interpretation);
        $this->assertEquals('fake_id', $updated_simultaneous_interpretation->id);
    }

    public function test_error_on_meeting_update_simultaneous_interpretation()
    {
        Http::fake([
            'meetings/fake_meeting_id/simultaneousInterpretation' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeUpdateSimultaneousInterpretation(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->updateSimultaneousInterpretation('fake_meeting_id', 'fake_enabled');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_create_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeCreateInterpreter()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $new_interpreter_details = $laravel_webex->meeting()->createInterpreter('fake_meeting_id', 'fake_language_code_1', 'fake_language_code_2');

        $this->assertInstanceOf(Meeting::class, $new_interpreter_details);
        $this->assertEquals('fake_id', $new_interpreter_details->id);
    }

    public function test_error_on_meeting_create_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeCreateInterpreter(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->createInterpreter('fake_meeting_id', 'fake_language_code_1', 'fake_language_code_2');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailInterpreter()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $interpreter_details = $laravel_webex->meeting()->detailInterpreter('fake_meeting_id', 'fake_interpreter_id');

        $this->assertInstanceOf(Meeting::class, $interpreter_details);
        $this->assertEquals('fake_id', $interpreter_details->id);
    }

    public function test_error_on_meeting_detail_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailInterpreter(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailInterpreter('fake_meeting_id', 'fake_interpreter_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_interpreters()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListInterpreters()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $interpreters = $laravel_webex->meeting()->listInterpreters('fake_meeting_id');

        $this->assertCount(2, $interpreters);

        $single_interpreter = null;
        foreach ($interpreters as $interpreter) {
            $this->assertInstanceOf(Meeting::class, $interpreter);
            $single_interpreter = $interpreter;
        }

        $this->assertEquals('fake_id', $single_interpreter->id);
    }

    public function test_error_on_meeting_list_interpreters()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListInterpreters(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listInterpreters('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_update_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeUpdateInterpreters()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_interpreter = $laravel_webex->meeting()->updateInterpreter('fake_meeting_id', 'fake_interpreter_id', 'fake_language_code_1', 'fake_language_code_2');

        $this->assertInstanceOf(Meeting::class, $updated_interpreter);
        $this->assertEquals('fake_id', $updated_interpreter->id);
    }

    public function test_error_on_meeting_update_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeUpdateInterpreters(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->updateInterpreter('fake_meeting_id', 'fake_interpreter_id', 'fake_language_code_1', 'fake_language_code_2');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_destroy_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDestroyInterpreter()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroyInterpreter('fake_meeting_id', 'fake_interpreter_id');

        $this->assertEquals('Meeting Interpreter deleted', $delete_response);
    }

    public function test_error_on_meeting_destroy_interpreter()
    {
        Http::fake([
            'meetings/fake_meeting_id/interpreters/fake_interpreter_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDestroyInterpreter(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->destroyInterpreter('fake_meeting_id', 'fake_interpreter_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_breakout_sessions()
    {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListBreakoutSessions()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $breakout_sessions = $laravel_webex->meeting()->listBreakoutSessions('fake_meeting_id');

        $this->assertCount(2, $breakout_sessions);

        $single_breakout_session = null;
        foreach ($breakout_sessions as $breakout_session) {
            $this->assertInstanceOf(Meeting::class, $breakout_session);
            $single_breakout_session = $breakout_session;
        }

        $this->assertEquals('fake_id', $single_breakout_session->id);
    }

    public function test_error_on_meeting_list_breakout_sessions()
    {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListBreakoutSession(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listBreakoutSessions('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_update_breakout_sessions()
    {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeUpdateBreakoutSession()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_breakout_session = $laravel_webex->meeting()->updateBreakoutSessions('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $updated_breakout_session);
        $this->assertEquals('fake_id', $updated_breakout_session->id);
    }

    public function test_error_on_meeting_update_breakout_sessions()
    {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeUpdateBreakoutSession(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->updateBreakoutSessions('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_destroy_breakout_sessions()
    {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDestroyBreakoutSession()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroyBreakoutSessions('fake_meeting_id');

        $this->assertEquals('Meeting Breakout Sessions deleted', $delete_response);
    }

    public function test_error_on_meeting_destroy_breakout_sessions()
    {
        Http::fake([
            'meetings/fake_meeting_id/breakoutSessions' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDestroyBreakoutSessions(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->destroyBreakoutSessions('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_survey()
    {
        Http::fake([
            'meetings/fake_meeting_id/survey' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailSurvey()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $survey_details = $laravel_webex->meeting()->detailSurvey('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $survey_details);
        $this->assertEquals('fake_id', $survey_details->id);
    }

    public function test_error_on_meeting_detail_survey()
    {
        Http::fake([
            'meetings/fake_meeting_id/survey' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailSurvey(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailSurvey('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_survey_results()
    {
        Http::fake([
            'meetings/fake_meeting_id/surveyResults' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListSurveyResults()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $survey_results = $laravel_webex->meeting()->listSurveyResults('fake_meeting_id');

        $this->assertCount(2, $survey_results);

        $single_survey_result = null;
        foreach ($survey_results as $survey_result) {
            $this->assertInstanceOf(Meeting::class, $survey_result);
            $single_survey_result = $survey_result;
        }

        $this->assertEquals('fake_id', $single_survey_result->id);
    }

    public function test_error_on_meeting_list_survey_results()
    {
        Http::fake([
            'meetings/fake_meeting_id/surveyResults' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListSurveyResults(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listSurveyResults('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_detail_survey_links()
    {
        Http::fake([
            'meetings/fake_meeting_id/surveyLinks' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeDetailSurveyLinks()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $survey_links_details = $laravel_webex->meeting()->detailSurveyLinks('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $survey_links_details);
        $this->assertEquals('fake_id', $survey_links_details->id);
    }

    public function test_error_on_meeting_detail_survey_links()
    {
        Http::fake([
            'meetings/fake_meeting_id/surveyLinks' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeDetailSurveyLinks(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->detailSurveyLinks('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_create_invitation_sources()
    {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeCreateInvitationSources()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $new_invitation_sources_details = $laravel_webex->meeting()->createInvitationSources('fake_meeting_id');

        $this->assertInstanceOf(Meeting::class, $new_invitation_sources_details);
        $this->assertEquals('fake_id', $new_invitation_sources_details->id);
    }

    public function test_error_on_meeting_create_invitation_sources()
    {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeCreateInvitationSources(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->createInvitationSources('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_invitation_sources()
    {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListInvitationSources()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $invitation_sources = $laravel_webex->meeting()->listInvitationSources('fake_meeting_id');

        $this->assertCount(2, $invitation_sources);

        $single_invitation_source = null;
        foreach ($invitation_sources as $invitation_source) {
            $this->assertInstanceOf(Meeting::class, $invitation_source);
            $single_invitation_source = $invitation_source;
        }

        $this->assertEquals('fake_id', $single_invitation_source->id);
    }

    public function test_error_on_meeting_list_invitation_sources()
    {
        Http::fake([
            'meetings/fake_meeting_id/invitationSources' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListInvitationSources(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listInvitationSources('fake_meeting_id');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_list_tracking_codes()
    {
        Http::fake([
            'meetings/trackingCodes' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeListTrackingCodes()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $tracking_codes = $laravel_webex->meeting()->listTrackingCodes('fake_service');

        $this->assertCount(2, $tracking_codes);

        $single_tracking_code = null;
        foreach ($tracking_codes as $tracking_code) {
            $this->assertInstanceOf(Meeting::class, $tracking_code);
            $single_tracking_code = $tracking_code;
        }

        $this->assertEquals('fake_id', $single_tracking_code->id);
    }

    public function test_error_on_meeting_list_tracking_codes()
    {
        Http::fake([
            'meetings/trackingCodes' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeListTrackingCodes(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->listTrackingCodes('fake_service');

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    public function test_meetings_reassign_to_new_host()
    {
        Http::fake([
            'meetings/reassignHost' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeReassignToNewHost()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $reassign_to_new_host = $laravel_webex->meeting()->reassignToNewHost('fake_host_email', ['fake_meeting_id_1', 'fake_meeting_id_2']);

        $this->assertInstanceOf(Meeting::class, $reassign_to_new_host);
        $this->assertEquals('fake_id', $reassign_to_new_host->id);
    }

    public function test_error_on_meeting_reassign_to_new_host()
    {
        Http::fake([
            'meetings/reassignHost' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeReassignToNewHost(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->reassignToNewHost('fake_host_email', ['fake_meeting_id_1', 'fake_meeting_id_2']);

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }
}
