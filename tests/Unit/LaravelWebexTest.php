<?php

use Illuminate\Support\Facades\Event;
use Offlineagency\LaravelWebex\Api\Admin\AdminAuditEvents;
use Offlineagency\LaravelWebex\Api\Admin\HistoricalAnalytics as AdminHistoricalAnalytics;
use Offlineagency\LaravelWebex\Api\Admin\HybridClusters;
use Offlineagency\LaravelWebex\Api\Admin\HybridConnectors;
use Offlineagency\LaravelWebex\Api\Admin\Licenses;
use Offlineagency\LaravelWebex\Api\Admin\Locations;
use Offlineagency\LaravelWebex\Api\Admin\Organizations;
use Offlineagency\LaravelWebex\Api\Admin\Reports;
use Offlineagency\LaravelWebex\Api\Admin\ReportTemplates;
use Offlineagency\LaravelWebex\Api\Admin\ResourceGroupMemberships;
use Offlineagency\LaravelWebex\Api\Admin\ResourceGroups;
use Offlineagency\LaravelWebex\Api\Admin\Roles;
use Offlineagency\LaravelWebex\Api\Admin\SpaceClassifications;
use Offlineagency\LaravelWebex\Api\Admin\WorkspaceLocations as AdminWorkspaceLocations;
use Offlineagency\LaravelWebex\Api\Admin\WorkspaceMetrics as AdminWorkspaceMetrics;
use Offlineagency\LaravelWebex\Api\Admin\Workspaces as AdminWorkspaces;
use Offlineagency\LaravelWebex\Api\Calling\BroadWorksEnterprises;
use Offlineagency\LaravelWebex\Api\Calling\BroadWorksSubscribers;
use Offlineagency\LaravelWebex\Api\Calling\CallControls;
use Offlineagency\LaravelWebex\Api\Calling\VoiceMessaging;
use Offlineagency\LaravelWebex\Api\Devices\DeviceConfigurations;
use Offlineagency\LaravelWebex\Api\Devices\Devices;
use Offlineagency\LaravelWebex\Api\Devices\WorkspaceLocations as DeviceWorkspaceLocations;
use Offlineagency\LaravelWebex\Api\Devices\WorkspaceMetrics as DeviceWorkspaceMetrics;
use Offlineagency\LaravelWebex\Api\Devices\Workspaces as DeviceWorkspaces;
use Offlineagency\LaravelWebex\Api\Devices\Xapi;
use Offlineagency\LaravelWebex\Api\Meetings\Meeting;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingChats;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingClosedCaptions;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingInvitee;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingMessages;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingParticipant;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingPolls;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingPreferences;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingQAndA;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingQualities;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingsSummaryReport;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingTranscripts;
use Offlineagency\LaravelWebex\Api\Meetings\People;
use Offlineagency\LaravelWebex\Api\Meetings\RecordingReport;
use Offlineagency\LaravelWebex\Api\Meetings\Recordings;
use Offlineagency\LaravelWebex\Api\Meetings\SessionTypes;
use Offlineagency\LaravelWebex\Api\Meetings\TrackingCodes;
use Offlineagency\LaravelWebex\Api\Meetings\VideoMesh;
use Offlineagency\LaravelWebex\Api\Meetings\Webhooks;
use Offlineagency\LaravelWebex\Api\Messages\AttachmentActions;
use Offlineagency\LaravelWebex\Api\Messages\Events;
use Offlineagency\LaravelWebex\Api\Messages\Memberships;
use Offlineagency\LaravelWebex\Api\Messages\Messages;
use Offlineagency\LaravelWebex\Api\Messages\Rooms;
use Offlineagency\LaravelWebex\Api\Messages\RoomTabs;
use Offlineagency\LaravelWebex\Api\Messages\TeamMemberships;
use Offlineagency\LaravelWebex\Api\Messages\Teams;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('LaravelWebex API accessors', function () {
    $accessors = [
        'meeting' => Meeting::class,
        'meeting_invitees' => MeetingInvitee::class,
        'meeting_participants' => MeetingParticipant::class,
        'meeting_chats' => MeetingChats::class,
        'meeting_closed_captions' => MeetingClosedCaptions::class,
        'meeting_messages' => MeetingMessages::class,
        'meeting_polls' => MeetingPolls::class,
        'meeting_preferences' => MeetingPreferences::class,
        'meeting_q_and_a' => MeetingQAndA::class,
        'meeting_qualities' => MeetingQualities::class,
        'meeting_transcripts' => MeetingTranscripts::class,
        'meetings_summary_report' => MeetingsSummaryReport::class,
        'people' => People::class,
        'recording_report' => RecordingReport::class,
        'recordings' => Recordings::class,
        'session_types' => SessionTypes::class,
        'tracking_codes' => TrackingCodes::class,
        'video_mesh' => VideoMesh::class,
        'webhooks' => Webhooks::class,
        'messages' => Messages::class,
        'rooms' => Rooms::class,
        'teams' => Teams::class,
        'memberships' => Memberships::class,
        'attachment_actions' => AttachmentActions::class,
        'events' => Events::class,
        'room_tabs' => RoomTabs::class,
        'team_memberships' => TeamMemberships::class,
        'admin_audit_events' => AdminAuditEvents::class,
        'admin_historical_analytics' => AdminHistoricalAnalytics::class,
        'admin_licenses' => Licenses::class,
        'admin_locations' => Locations::class,
        'admin_organizations' => Organizations::class,
        'admin_report_templates' => ReportTemplates::class,
        'admin_reports' => Reports::class,
        'admin_resource_groups' => ResourceGroups::class,
        'admin_resource_group_memberships' => ResourceGroupMemberships::class,
        'admin_roles' => Roles::class,
        'admin_space_classifications' => SpaceClassifications::class,
        'admin_workspace_locations' => AdminWorkspaceLocations::class,
        'admin_workspace_metrics' => AdminWorkspaceMetrics::class,
        'admin_workspaces' => AdminWorkspaces::class,
        'admin_hybrid_clusters' => HybridClusters::class,
        'admin_hybrid_connectors' => HybridConnectors::class,
        'call_controls' => CallControls::class,
        'broadworks_enterprises' => BroadWorksEnterprises::class,
        'broadworks_subscribers' => BroadWorksSubscribers::class,
        'voice_messaging' => VoiceMessaging::class,
        'device_configurations' => DeviceConfigurations::class,
        'devices' => Devices::class,
        'device_workspaces' => DeviceWorkspaces::class,
        'device_workspace_locations' => DeviceWorkspaceLocations::class,
        'device_workspace_metrics' => DeviceWorkspaceMetrics::class,
        'xapi' => Xapi::class,
    ];

    foreach ($accessors as $method => $expectedClass) {
        it("returns {$expectedClass} from {$method}()", function () use ($method, $expectedClass) {
            Event::fake([AuthenticationRequested::class, SuccessfulAuthentication::class]);

            $laravel_webex = new LaravelWebex;
            $instance = $laravel_webex->{$method}();

            expect($instance)->toBeInstanceOf($expectedClass);
        });
    }
});
