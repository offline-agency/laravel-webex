<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Offlineagency\LaravelWebex\LaravelWebex
 *
 * Main accessors: meeting(), meeting_invitees(), meeting_participants(), meeting_chats(), meeting_closed_captions(),
 * meeting_messages(), meeting_polls(), meeting_preferences(), meeting_q_and_a(), meeting_qualities(),
 * meeting_transcripts(), meetings_summary_report(), people(), recording_report(), recordings(), session_types(),
 * tracking_codes(), video_mesh(), webhooks(), messages(), rooms(), teams(), memberships(), attachment_actions(),
 * events(), room_tabs(), team_memberships(), admin_audit_events(), admin_historical_analytics(), admin_licenses(),
 * admin_locations(), admin_organizations(), admin_report_templates(), admin_reports(), admin_resource_groups(),
 * admin_resource_group_memberships(), admin_roles(), admin_space_classifications(), admin_workspace_locations(),
 * admin_workspace_metrics(), admin_workspaces(), admin_hybrid_clusters(), admin_hybrid_connectors(),
 * call_controls(), broadworks_enterprises(), broadworks_subscribers(), voice_messaging(), device_configurations(),
 * devices(), device_workspaces(), device_workspace_locations(), device_workspace_metrics(), xapi()
 */
class LaravelWebexFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-webex';
    }
}
