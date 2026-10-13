# Upgrade guide

## From 1.x to 2.0

2.0 is a major release: it drops platforms and changes a few public behaviours.

### Platforms

| | 1.x | 2.0 |
| --- | --- | --- |
| PHP | 7.1+ (1.0/1.1) | 8.4 and 8.5 only (`~8.4.0 \|\| ~8.5.0`) |
| Laravel | 5 – 9 (1.0/1.1) | 12 (from 12.69.2) and 13 (from 13.32) |

The package now requires `laravel/framework` (it uses `Illuminate\Foundation` classes, which no `illuminate/*` component provides). Laravel 11 and PHP 8.3 are not supported; upgrade the application first.

### Code changes

- `Messages::delete($id)` is now `Messages::destroy($id)` (the old name collided with the HTTP helper of the base class).
- `Webhooks::createWebhook()` takes the resource and the event as strings, as the Webex API requires:
  ```php
  // 1.x
  createWebhook('Name', $url, ['meetings'], ['created']);
  // 2.0
  createWebhook('Name', $url, 'meetings', 'created');
  ```

### Endpoints aligned with the Webex API

Every endpoint method is now checked against the official OpenAPI specification. Requests that did not exist in the API were fixed; if you relied on them, nothing worked before:

- paths: `adminAudit/events`, `hybrid/clusters`, `hybrid/connectors`, `report/templates`, `resourceGroup/memberships`, `classifications` (space classifications), `broadworks/enterprises`, `broadworks/subscribers`, `telephony/voiceMessages`, `videoMesh/testResults/...`, `admin/meetingPreferences/personalMeetingRoom/refreshId`;
- methods: invitee and participant `update()` use `PUT`, `listTrackingCodes()` `GET`, `detailSurveyLinks()` `POST`, the video mesh `triggerOnDemandTestFor*()` `POST`, `updateEventThresholdConfiguration()` `PATCH`, `destroyChats()` sends `meetingId` as a query parameter;
- `Xapi::command()` posts to `xapi/command/{command}` with the device in the body; `Xapi::status()` queries `xapi/status?deviceId=&name=`;
- `TrackingCodes::updateTrackingCode($trackingCodeId, $name, ...)` takes the id of the tracking code first;
- `Meeting::batchUpdateRegistrantsStatus()` accepts `approve`, `reject`, `cancel` or `bulkDelete` and returns an `Error` for anything else.

Removed, because the Webex API has no such endpoint: `create()`/`update()`/`destroy()` of `admin_hybrid_clusters()`, `admin_hybrid_connectors()` and `admin_resource_groups()`; `detail()` of `admin_report_templates()`, `broadworks_enterprises()` and `voice_messaging()`; `create()`/`destroy()` of `admin_resource_group_memberships()`; `detail()`/`create()`/`update()`/`destroy()` of `admin_space_classifications()`; `admin_historical_analytics()`.

### Behaviour changes

- The bearer token is resolved for every request (`forToken()`, `webex.token_resolver`, then `webex.bearer`): a long-lived client sees a changed configuration.
- `GET`, `PUT` and `DELETE` are retried after network failures, 429 and 503 (`webex.retry`); `Error` has `status` and `retryAfter`.
- The `/auth` route is off by default (`WEBEX_AUTH_ROUTE_ENABLED=true`). `WebexClientConfigured` is dispatched when a client is created; `SuccessfulAuthentication` is deprecated.
- Entity properties are typed (`?string`, `?int`, ...) where the API schema is unambiguous; a value of another type is ignored.

- Any 2xx status is a success: `201 Created` and `202 Accepted` responses return the entity instead of an `Error`.
- A successful response with malformed JSON, a JSON scalar, or no document where an entity is expected returns an `Error` instead of an empty list or an empty entity.
- Failed responses whose body is not a Webex error document return `Error("Webex request failed (HTTP n).")` instead of `"Unknown error"`; network failures return `"Connection to Webex failed: <reason>"`.
- Exceptions that are not network failures (for example a `TypeError` caused by a bug) are thrown instead of being returned as an `Error`.
- A field whose value does not fit a typed entity property is ignored instead of throwing a `TypeError`.
- `SuccessfulAuthentication` is dispatched after the HTTP client is built. Set a dynamic token in an `AuthenticationRequested` listener; changing it in a `SuccessfulAuthentication` listener no longer affects the client.
- The `/auth` route is registered while the provider boots and can be moved or disabled with `webex.auth_route` (defaults unchanged). Republish the configuration or add the key to see the option.

### Development (contributors)

Pest 4 (Pest 5 requires Laravel 13 only), Testbench 10/11, Larastan at level max over `src`, `Http`, `config`, `routes`, `tests` and the release tooling. Releases are created automatically after merges into `main` (see CONTRIBUTING.md).
