<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
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
use Offlineagency\LaravelWebex\Api\Meetings\MeetingTranscripts;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingsSummaryReport;
use Offlineagency\LaravelWebex\Api\Meetings\People;
use Offlineagency\LaravelWebex\Api\Meetings\RecordingReport;
use Offlineagency\LaravelWebex\Api\Meetings\Recordings;
use Offlineagency\LaravelWebex\Api\Meetings\SessionTypes;
use Offlineagency\LaravelWebex\Api\Meetings\TrackingCodes;
use Offlineagency\LaravelWebex\Api\Meetings\VideoMesh;
use Offlineagency\LaravelWebex\Api\Meetings\Webhooks;
use Offlineagency\LaravelWebex\Api\Admin\AdminAuditEvents;
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
use Offlineagency\LaravelWebex\Api\Admin\HistoricalAnalytics as AdminHistoricalAnalytics;
use Offlineagency\LaravelWebex\Api\Admin\HybridClusters;
use Offlineagency\LaravelWebex\Api\Admin\HybridConnectors;
use Offlineagency\LaravelWebex\Api\Admin\Licenses;
use Offlineagency\LaravelWebex\Api\Admin\Locations;
use Offlineagency\LaravelWebex\Api\Admin\Organizations;
use Offlineagency\LaravelWebex\Api\Admin\ReportTemplates;
use Offlineagency\LaravelWebex\Api\Admin\Reports;
use Offlineagency\LaravelWebex\Api\Admin\ResourceGroupMemberships;
use Offlineagency\LaravelWebex\Api\Admin\ResourceGroups;
use Offlineagency\LaravelWebex\Api\Admin\Roles;
use Offlineagency\LaravelWebex\Api\Admin\SpaceClassifications;
use Offlineagency\LaravelWebex\Api\Admin\WorkspaceLocations;
use Offlineagency\LaravelWebex\Api\Admin\WorkspaceMetrics;
use Offlineagency\LaravelWebex\Api\Admin\Workspaces as AdminWorkspaces;
use Offlineagency\LaravelWebex\Api\Messages\AttachmentActions;
use Offlineagency\LaravelWebex\Api\Messages\Events;
use Offlineagency\LaravelWebex\Api\Messages\Memberships;
use Offlineagency\LaravelWebex\Api\Messages\Messages;
use Offlineagency\LaravelWebex\Api\Messages\RoomTabs;
use Offlineagency\LaravelWebex\Api\Messages\Rooms;
use Offlineagency\LaravelWebex\Api\Messages\TeamMemberships;
use Offlineagency\LaravelWebex\Api\Messages\Teams;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;

class LaravelWebex
{
    public string $base_url;

    public PendingRequest $httpBuilder;

    public function __construct()
    {
        $this->setBaseUrl();

        event(new AuthenticationRequested());

        $this->setHeader();
    }

    public function meeting(): Meeting
    {
        return new Meeting($this);
    }

    public function meeting_invitees(): MeetingInvitee
    {
        return new MeetingInvitee($this);
    }

    public function meeting_participants(): MeetingParticipant
    {
        return new MeetingParticipant($this);
    }

    public function meeting_chats(): MeetingChats
    {
        return new MeetingChats($this);
    }

    public function meeting_closed_captions(): MeetingClosedCaptions
    {
        return new MeetingClosedCaptions($this);
    }

    public function meeting_messages(): MeetingMessages
    {
        return new MeetingMessages($this);
    }

    public function meeting_polls(): MeetingPolls
    {
        return new MeetingPolls($this);
    }

    public function meeting_preferences(): MeetingPreferences
    {
        return new MeetingPreferences($this);
    }

    public function meeting_q_and_a(): MeetingQAndA
    {
        return new MeetingQAndA($this);
    }

    public function meeting_qualities(): MeetingQualities
    {
        return new MeetingQualities($this);
    }

    public function meeting_transcripts(): MeetingTranscripts
    {
        return new MeetingTranscripts($this);
    }

    public function meetings_summary_report(): MeetingsSummaryReport
    {
        return new MeetingsSummaryReport($this);
    }

    public function people(): People
    {
        return new People($this);
    }

    public function recording_report(): RecordingReport
    {
        return new RecordingReport($this);
    }

    public function recordings(): Recordings
    {
        return new Recordings($this);
    }

    public function session_types(): SessionTypes
    {
        return new SessionTypes($this);
    }

    public function tracking_codes(): TrackingCodes
    {
        return new TrackingCodes($this);
    }

    public function video_mesh(): VideoMesh
    {
        return new VideoMesh($this);
    }

    public function webhooks(): Webhooks
    {
        return new Webhooks($this);
    }

    public function messages(): Messages
    {
        return new Messages($this);
    }

    public function rooms(): Rooms
    {
        return new Rooms($this);
    }

    public function teams(): Teams
    {
        return new Teams($this);
    }

    public function memberships(): Memberships
    {
        return new Memberships($this);
    }

    public function attachment_actions(): AttachmentActions
    {
        return new AttachmentActions($this);
    }

    public function events(): Events
    {
        return new Events($this);
    }

    public function room_tabs(): RoomTabs
    {
        return new RoomTabs($this);
    }

    public function team_memberships(): TeamMemberships
    {
        return new TeamMemberships($this);
    }

    public function admin_audit_events(): AdminAuditEvents
    {
        return new AdminAuditEvents($this);
    }

    public function admin_historical_analytics(): AdminHistoricalAnalytics
    {
        return new AdminHistoricalAnalytics($this);
    }

    public function admin_licenses(): Licenses
    {
        return new Licenses($this);
    }

    public function admin_locations(): Locations
    {
        return new Locations($this);
    }

    public function admin_organizations(): Organizations
    {
        return new Organizations($this);
    }

    public function admin_report_templates(): ReportTemplates
    {
        return new ReportTemplates($this);
    }

    public function admin_reports(): Reports
    {
        return new Reports($this);
    }

    public function admin_resource_groups(): ResourceGroups
    {
        return new ResourceGroups($this);
    }

    public function admin_resource_group_memberships(): ResourceGroupMemberships
    {
        return new ResourceGroupMemberships($this);
    }

    public function admin_roles(): Roles
    {
        return new Roles($this);
    }

    public function admin_space_classifications(): SpaceClassifications
    {
        return new SpaceClassifications($this);
    }

    public function admin_workspace_locations(): WorkspaceLocations
    {
        return new WorkspaceLocations($this);
    }

    public function admin_workspace_metrics(): WorkspaceMetrics
    {
        return new WorkspaceMetrics($this);
    }

    public function admin_workspaces(): AdminWorkspaces
    {
        return new AdminWorkspaces($this);
    }

    public function admin_hybrid_clusters(): HybridClusters
    {
        return new HybridClusters($this);
    }

    public function admin_hybrid_connectors(): HybridConnectors
    {
        return new HybridConnectors($this);
    }

    public function call_controls(): CallControls
    {
        return new CallControls($this);
    }

    public function broadworks_enterprises(): BroadWorksEnterprises
    {
        return new BroadWorksEnterprises($this);
    }

    public function broadworks_subscribers(): BroadWorksSubscribers
    {
        return new BroadWorksSubscribers($this);
    }

    public function voice_messaging(): VoiceMessaging
    {
        return new VoiceMessaging($this);
    }

    public function device_configurations(): DeviceConfigurations
    {
        return new DeviceConfigurations($this);
    }

    public function devices(): Devices
    {
        return new Devices($this);
    }

    public function device_workspaces(): DeviceWorkspaces
    {
        return new DeviceWorkspaces($this);
    }

    public function device_workspace_locations(): DeviceWorkspaceLocations
    {
        return new DeviceWorkspaceLocations($this);
    }

    public function device_workspace_metrics(): DeviceWorkspaceMetrics
    {
        return new DeviceWorkspaceMetrics($this);
    }

    public function xapi(): Xapi
    {
        return new Xapi($this);
    }

    private function setBaseUrl(): void
    {
        $this->base_url = config('webex.base_url');
    }

    private function setHeader(): void
    {
        event(new SuccessfulAuthentication());

        $this->httpBuilder = Http::withHeaders([
            'Authorization' => 'Bearer '.config('webex.bearer'),
        ])->timeout(config('webex.timeout', 30));
    }
}
