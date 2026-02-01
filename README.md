# Laravel Webex

[![Latest Stable Version](https://poser.pugx.org/offline-agency/laravel-webex/v/stable)](https://packagist.org/packages/offline-agency/laravel-webex)
[![Total Downloads](https://img.shields.io/packagist/dt/offline-agency/laravel-webex.svg?style=flat-square)](https://packagist.org/packages/offline-agency/laravel-webex)
[![run-tests](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml/badge.svg)](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![codecov](https://codecov.io/gh/offline-agency/laravel-webex/branch/develop/graph/badge.svg?token=0BHADJQYAW)](https://codecov.io/gh/offline-agency/laravel-webex)

A simple Laravel integration with Webex (Meetings and Messaging APIs).

## Requirements

- PHP 8.4+
- Laravel 11+ (or 12+)
- Webex API bearer token

## Installation

Install the package via Composer:

```bash
composer require offline-agency/laravel-webex
```

### Configuration

Publish the config file using either:

```bash
php artisan vendor:publish --provider="Offlineagency\LaravelWebex\Providers\LaravelWebexServiceProvider"
```

or:

```bash
php artisan vendor:publish --tag=webex-config
```

Then set in your `.env` (do not commit secrets):

- `WEBEX_BEARER` – Bearer token for API requests (required)
- `WEBEX_TIMEOUT` – Request timeout in seconds (default: 30)
- `WEBEX_CLIENT_ID`, `WEBEX_CLIENT_SECRET`, `WEBEX_CLIENT_CODE`, `WEBEX_REDIRECT_URI` – For OAuth flows

### Authentication

The package uses a **Bearer token** for all API requests. Set `WEBEX_BEARER` in your environment or in `config/webex.php` after publishing.

Two events are fired during the auth flow:

- `Offlineagency\LaravelWebex\Events\AuthenticationRequested` – before the HTTP client is built
- `Offlineagency\LaravelWebex\Events\SuccessfulAuthentication` – after headers (including Authorization) are set

You can listen to these to inject or refresh the token dynamically.

## API coverage

✅ = implemented and exposed on the facade  
❌ = not implemented

### Meetings

- ✅ **Meetings** – list, detail, create, update, delete, join, templates, controls, session types, registration, registrants, interpreters, breakout, survey, invitation sources, tracking codes, reassign host
- ✅ **Meeting Invitees** – list, detail, create, bulk create, update, delete
- ✅ **Meeting Participants** – list, query, detail, update, admit
- ✅ **Meeting Preferences** – get/update preferences, personal meeting room, audio/video, scheduling, sites
- ✅ **Meeting Qualities**
- ✅ **Meeting Transcripts** – list, download, snippets
- ✅ **Meeting Chats** – list, delete
- ✅ **Meeting Polls / Q&A / Closed Captions / Messages**
- ✅ **Meetings Summary Report**
- ✅ **People** (Meetings context) – list, create, detail, update, delete
- ✅ **Recording Report**
- ✅ **Recordings** – list, detail, delete, admin, soft delete, restore, purge
- ✅ **Session Types** / **Tracking Codes**
- ✅ **VideoMesh**
- ✅ **Webhooks** – list, create, detail, update, delete

### Messaging

- ✅ **Messages** – list, listWithPagination, create, detail, edit, destroy
- ✅ **Rooms** – list, listWithPagination, create, detail, update, destroy, meetingDetails
- ✅ **Teams** – list, create, detail, update, destroy
- ✅ **Memberships** – list, create, detail, update, destroy
- ✅ **Attachment Actions** – create, detail
- ✅ **Events** – list
- ✅ **Room Tabs** – list, create, detail, update, destroy
- ✅ **Team Memberships** – list, create, detail, update, destroy

### Admin / Calling / Devices

- ✅ **Admin** – Audit Events, Historical Analytics, Hybrid Clusters/Connectors, Licenses, Locations, Organizations, Report Templates, Reports, Resource Groups, Resource Group Memberships, Roles, Space Classifications, Workspace Locations/Metrics, Workspaces
- ✅ **Calling** – Call Controls, BroadWorks Enterprises/Subscribers, Voice Messaging
- ✅ **Devices** – Device Configurations, Devices, Workspaces, Workspace Locations, Workspace Metrics, xAPI

Endpoints that support pagination expose `listWithPagination()` and return `['items' => [...], 'nextLink' => string|null]`.

## Usage examples

### Meetings

```php
use LaravelWebex;

// List meetings
$meetings = LaravelWebex::meeting()->list();
$meetings = LaravelWebex::meeting()->list(['state' => 'inProgress', 'max' => 10]);

// Get one meeting
$meeting = LaravelWebex::meeting()->detail($meetingId);

// Create a meeting
$meeting = LaravelWebex::meeting()->create('My Meeting', '2025-02-15T10:00:00Z', '2025-02-15T11:00:00Z', [
    'agenda' => 'Agenda here',
    'enabledAutoRecordMeeting' => true,
]);

// Update / delete
LaravelWebex::meeting()->update($meetingId, $title, $password, $start, $end);
LaravelWebex::meeting()->destroy($meetingId);

// Invitees and participants
$invitees = LaravelWebex::meeting_invitees()->list($meetingId);
$participants = LaravelWebex::meeting_participants()->list($meetingId);
```

### Messaging (Messages, Rooms, Teams, Memberships, People, Webhooks)

```php
use LaravelWebex;

// List messages in a room (with optional pagination info)
$messages = LaravelWebex::messages()->list($roomId);
$result = LaravelWebex::messages()->listWithPagination($roomId, ['max' => 20]);
// $result['items'], $result['nextLink']

// Create / get / edit / delete a message
$msg = LaravelWebex::messages()->create($roomId, 'Hello!');
$msg = LaravelWebex::messages()->detail($messageId);
$msg = LaravelWebex::messages()->edit($messageId, 'Updated text');
LaravelWebex::messages()->destroy($messageId);

// Rooms
$rooms = LaravelWebex::rooms()->list();
$result = LaravelWebex::rooms()->listWithPagination(['max' => 20]);
$room = LaravelWebex::rooms()->create('My Room');
$room = LaravelWebex::rooms()->detail($roomId);
LaravelWebex::rooms()->update($roomId, ['title' => 'New Title']);
$room = LaravelWebex::rooms()->meetingDetails($roomId);
LaravelWebex::rooms()->destroy($roomId);

// Teams
$teams = LaravelWebex::teams()->list();
$team = LaravelWebex::teams()->create('My Team');
$team = LaravelWebex::teams()->detail($teamId);
LaravelWebex::teams()->update($teamId, 'New Name');
LaravelWebex::teams()->destroy($teamId);

// Memberships
$memberships = LaravelWebex::memberships()->list();
$membership = LaravelWebex::memberships()->create($roomId, 'user@example.com');
$membership = LaravelWebex::memberships()->detail($membershipId);
LaravelWebex::memberships()->update($membershipId, ['isModerator' => true]);
LaravelWebex::memberships()->destroy($membershipId);

// People (Meetings API)
$people = LaravelWebex::people()->listPeople();
$person = LaravelWebex::people()->detailPerson($personId);

// Webhooks
$webhooks = LaravelWebex::webhooks()->listWebhooks();
$webhook = LaravelWebex::webhooks()->createWebhook('My Webhook', 'https://example.com/webhook', ['meetings'], ['created']);
LaravelWebex::webhooks()->destroyWebhook($webhookId);
```

All methods that can fail return either the expected entity/array or an `Offlineagency\LaravelWebex\Entities\Error` instance. Check with `$result instanceof \Offlineagency\LaravelWebex\Entities\Error` and use `$result->message`, `$result->errors`, `$result->trackingId` as needed.

## Testing and static analysis

```bash
composer test
composer stan
```

- `composer test` – runs Pest (unit and feature tests).
- `./vendor/bin/pest` – run tests directly; use `./vendor/bin/pest --coverage-html coverage` for local HTML coverage.
- `composer stan` – runs PHPStan (Larastan) for static analysis.

## Documentation

Full documentation is available at [docs.offlineagency.com/laravel-webex](https://docs.offlineagency.com/laravel-webex/).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email <support@offlineagency.com> instead of using the issue tracker.

## Credits

- [Offline Agency](https://github.com/offline-agency)
- [Giacomo Fabbian](https://github.com/Giacomo92)
- [Nicolas Sanavia](https://github.com/SanaviaNicolas)
- [All Contributors](https://github.com/offline-agency/laravel-webex/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
