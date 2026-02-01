# Webex API Endpoint Implementation Coverage

Summary of what is implemented in this package vs exposed on the facade vs known gaps.

---

## 1. Exposed on `LaravelWebex` facade (usable in app)

These APIs are registered in `LaravelWebex.php` and are the only ones callable via `LaravelWebex::…()`.

### Meetings (all exposed)

| API class | Facade method | Operations implemented |
|-----------|----------------|------------------------|
| Meeting | `meeting()` | create, detail, list, listSeries, update, destroy, join, listTemplates, detailTemplate, controls (get/update), sessionTypes (list/detail), registration (get/update/delete), registrants (register, batchRegister, detail, list, query, batchUpdateStatus, destroy), simultaneousInterpretation, interpreters (create, detail, list, update, destroy), breakoutSessions (list, update, destroy), survey (detail, listResults, detailLinks), invitationSources (create, list), listTrackingCodes, reassignHost |
| MeetingInvitee | `meeting_invitees()` | list, detail, create, bulk_create, update, destroy |
| MeetingParticipant | `meeting_participants()` | list, queryWIthEmail, detail, update, admit |
| MeetingChats | `meeting_chats()` | listChats, destroyChats |
| MeetingMessages | `meeting_messages()` | **destroyMessage only** (no list/get) |
| MeetingPolls | `meeting_polls()` | listPolls, detailPollResults, listRespondentsQuestion |
| MeetingPreferences | `meeting_preferences()` | detailPreference, detailPersonalRoomOptions, updatePersonalRoomOptions, detailAudioOptions, detailVideoOptions, updateVideoOptions, detailSchedulingOptions, updateSchedulingOptions, insertDelegateEmails, deleteDelegateEmails, detailSiteList, updateDefaultSite, batchRefreshPersonalMeetingRoomID |
| MeetingQAndA | `meeting_q_and_a()` | listQAndA, listAnswersOfAQuestion |
| MeetingQualities | `meeting_qualities()` | detailQualities |
| MeetingClosedCaptions | `meeting_closed_captions()` | listClosedCaptions, listClosedCaptionSnippets, downloadClosedCaptionSnippets |
| MeetingTranscripts | `meeting_transcripts()` | listTranscripts, listTranscriptsForComplianceOfficer, downloadTranscript, listSnippetsOfTranscript, detailTranscriptSnippet, updateTranscriptSnippet, destroyTranscript |
| MeetingsSummaryReport | `meetings_summary_report()` | listUsageReports, listAttendeeReports |
| People | `people()` | listPeople, createPerson, detailPerson, updatePerson, destroyPerson, detailOwn |
| RecordingReport | `recording_report()` | listRecordingAuditReportSummaries, detailRecordAuditReport, listArchiveSummaries, detailArchive |
| Recordings | `recordings()` | listRecordings, listRecordingsForAnAdminOrComplianceOfficer, detailRecording, destroyRecording, moveRecordingsIntoRecycleBin, restoreRecordingsFromRecycleBin, purgeRecordingsFromRecycleBin |
| SessionTypes | `session_types()` | listSiteSessionTypes, listUserSessionType, update |
| TrackingCodes | `tracking_codes()` | listTrackingCodes, detailTrackingCode, createTrackingCode, updateTrackingCode, destroyTrackingCode, detailUserTrackingCodes, updateUserTrackingCodes |
| VideoMesh | `video_mesh()` | 30+ methods (clusters, nodes, media health, reachability, utilization, event thresholds, etc.) |
| Webhooks | `webhooks()` | listWebhooks, createWebhook, detailWebhook, updateWebhook (updateTrackingCode deprecated alias), destroyWebhook |

### Messaging (all exposed)

| API class | Facade method | Operations implemented |
|-----------|----------------|------------------------|
| Messages | `messages()` | list, listWithPagination, create, detail, edit, destroy |
| Rooms | `rooms()` | list, listWithPagination, create, detail, update, destroy, meetingDetails |
| Teams | `teams()` | list, create, detail, update, destroy |
| Memberships | `memberships()` | list, create, detail, update, destroy |
| AttachmentActions | `attachment_actions()` | create, detail |
| Events | `events()` | list |
| RoomTabs | `room_tabs()` | list, create, detail, update, destroy |
| TeamMemberships | `team_memberships()` | list, create, detail, update, destroy |

---

## 2. Admin, Calling, Devices (all exposed)

Admin, Calling, and Devices API classes are implemented in `src/Api/Admin`, `src/Api/Calling`, and `src/Api/Devices` and are **exposed** on the facade via `admin_*()`, `call_controls()`, `broadworks_*()`, `voice_messaging()`, `device_*()`, `xapi()`, etc. See `LaravelWebex.php` for the full list.

---

## 3. Gaps vs Webex API (missing operations)

| Area | Missing | Notes |
|------|--------|--------|
| **Meeting Messages** | List / Get single | Only **Delete** is implemented. Webex docs state meeting messages are exposed via the **Events API** with resource `meetingMessages`. To list or get meeting messages, use `LaravelWebex::events()->list(['resource' => 'meetingMessages', ...])`. There is no dedicated `GET meeting/messages` or `GET meeting/messages/{id}` in the Webex REST API. |

Room Tabs create and Webhooks naming (`updateWebhook()`) are implemented. README has been aligned with implementation.

---

## 4. Implemented and exposed

- **Calling**: Call Controls, BroadWorks Enterprises/Subscribers, Voice Messaging – all exposed via `call_controls()`, `broadworks_enterprises()`, `broadworks_subscribers()`, `voice_messaging()`.
- **Devices**: Device Configurations, Devices, Workspaces, Workspace Locations, Workspace Metrics, xAPI – all exposed via `device_configurations()`, `devices()`, `device_workspaces()`, `device_workspace_locations()`, `device_workspace_metrics()`, `xapi()`.
- **Admin**: Audit Events, Historical Analytics, Licenses, Locations, Organizations, Reports, Report Templates, Resource Groups, Resource Group Memberships, Roles, Space Classifications, Workspace Locations/Metrics, Workspaces, Hybrid Clusters/Connectors – all exposed via `admin_*()` methods.

---

## 5. Summary

| Category | Count |
|----------|--------|
| Meeting API classes exposed | 20 |
| Messaging API classes exposed | 8 |
| Admin API classes exposed | 16 |
| Calling API classes exposed | 4 |
| Devices API classes exposed | 6 |
| Known missing operations | Meeting Messages list/get (use Events API with `resource=meetingMessages`) |
