<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Meetings;

class MeetingsFakeResponse extends FakeResponse
{
    public function getMeetingsFakeList()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getFilteredMeetingsFakeList()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getMeetingsFakeListSeries()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListSeries()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingFakeDetail()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getNewMeetingFakeDetail()
    {
        return json_encode(
            $this->fakeMeeting([
                'agenda' => 'fake_created_agenda',
                'enabledAutoRecordMeeting' => true,
            ])
        );
    }

    public function getUpdatedMeetingFakeDetail()
    {
        return json_encode(
            $this->fakeMeeting([
                'agenda' => 'fake_updated_agenda',
                'enabledAutoRecordMeeting' => false,
            ])
        );
    }

    public function getDeleteMeetingFakeResponse()
    {
        return null;
    }

    public function getErrorOnMeetingsFakeList()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeJoinDetail()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeJoin()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListTemplates()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListTemplates()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailTemplate()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailTemplate()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailControlStatus()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeControlStatus()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeUpdateControlStatus()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeUpdateControlStatus()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListSessionTypes()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListSessionTypes()
    {
        return json_encode(
            $this->fakeError()
        );
    }
    public function getMeetingsFakeDetailSessionType()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailSessionTypes()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailRegistrationForm()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailRegistrationForm()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeUpdateRegistrationForm()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeUpdateRegistrationForm()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDestroyRegistrationForm()
    {
        return null;
    }

    public function getErrorOnMeetingsFakeDestroyRegistrationForm()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeRegister()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeRegister()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeBatchRegister()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeBatchRegister()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailInformationForRegistrant()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailInformationForRegistrant()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListRegistrants()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListRegistrants()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeQueryRegistrants()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeQueryRegistrants()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeUpdateRegistrantsStatus()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeUpdateRegistrantsStatus()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDestroyRegistrant()
    {
        return null;
    }

    public function getErrorOnMeetingsFakeDestroyRegistrant()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeUpdateSimultaneousInterpretation()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeUpdateSimultaneousInterpretation()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeCreateInterpreter()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeCreateInterpreter()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailInterpreter()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailInterpreter()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListInterpreters()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListInterpreters()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeUpdateInterpreters()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeUpdateInterpreters()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDestroyInterpreter()
    {
        return null;
    }

    public function getErrorOnMeetingsFakeDestroyInterpreter()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListBreakoutSessions()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListBreakoutSession()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeUpdateBreakoutSession()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeUpdateBreakoutSession()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDestroyBreakoutSession()
    {
        return null;
    }

    public function getErrorOnMeetingsFakeDestroyBreakoutSessions()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailSurvey()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailSurvey()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListSurveyResults()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListSurveyResults()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeDetailSurveyLinks()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeDetailSurveyLinks()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeCreateInvitationSources()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeCreateInvitationSources()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListInvitationSources()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListInvitationSources()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeListTrackingCodes()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting(),
            ],
        ]);
    }

    public function getErrorOnMeetingsFakeListTrackingCodes()
    {
        return json_encode(
            $this->fakeError()
        );
    }

    public function getMeetingsFakeReassignToNewHost()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }

    public function getErrorOnMeetingsFakeReassignToNewHost()
    {
        return json_encode(
            $this->fakeError()
        );
    }
}
