# Docs update for docs.offlineagency.com

Use this to align the live docs with the package after the PHP 8.4 / Laravel 11+ upgrade and new endpoints.

## Installation

- **Requirements:** PHP 8.4+, Laravel 11+ (or 12+).
- **Install:** `composer require offline-agency/laravel-webex`
- **Config:** Publish with either:
  - `php artisan vendor:publish --provider="Offlineagency\LaravelWebex\Providers\LaravelWebexServiceProvider"`
  - `php artisan vendor:publish --tag=webex-config`

## Authentication

- Bearer token via `config('webex.bearer')` (e.g. `WEBEX_BEARER` in `.env`).
- Events: `AuthenticationRequested`, `SuccessfulAuthentication`.

## API coverage (implemented)

- **Meetings:** Meetings, Meeting Invitees, Meeting Participants, Meeting Preferences, Meeting Qualities, Meeting Transcripts, Meeting Chats, Meeting Polls/Q&A/Closed Captions/Messages, Meetings Summary Report, People, Recording Report, Recordings, Session Types, Tracking Codes, VideoMesh, Webhooks.
- **Messaging:** Messages (list, listWithPagination, create, detail, edit, destroy), Rooms (list, listWithPagination, create, detail, update, destroy, meetingDetails), Teams (list, create, detail, update, destroy), Memberships (list, create, detail, update, destroy).

## Usage examples

See README.md sections "Usage examples" for Meetings and Messaging (Messages, Rooms, People, Webhooks).

## Testing and static analysis

- `composer test` – run PHPUnit.
- `composer stan` – run PHPStan (Larastan).

Update the docs site’s "Testing" section to include both commands.
