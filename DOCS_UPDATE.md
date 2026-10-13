# Docs update for docs.offlineagency.com

Use this to align the live docs with the package for the 2.0 release (PHP 8.4/8.5, Laravel 12/13) and the new endpoints.

## Installation

- **Requirements:** PHP 8.4 or 8.5, Laravel 12 (from 12.69.2) or 13 (from 13.32). See UPGRADE.md for 1.x users.
- **Install:** `composer require offline-agency/laravel-webex`
- **Config:** `php artisan vendor:publish --tag=webex-config`

## Authentication

- Bearer token via `config('webex.bearer')` (e.g. `WEBEX_BEARER` in `.env`).
- Events: `AuthenticationRequested` (set a dynamic token here), then `SuccessfulAuthentication` once the client is configured (not a proof that Webex accepted the token).
- Results: entity / list / `true`, or an `Error` (see README "Results and errors").

## API coverage (implemented)

- **Meetings:** Meetings, Meeting Invitees, Meeting Participants, Meeting Preferences, Meeting Qualities, Meeting Transcripts, Meeting Chats, Meeting Polls/Q&A/Closed Captions/Messages, Meetings Summary Report, People, Recording Report, Recordings, Session Types, Tracking Codes, VideoMesh, Webhooks.
- **Messaging:** Messages (list, listWithPagination, create, detail, edit, destroy), Rooms (list, listWithPagination, create, detail, update, destroy, meetingDetails), Teams (list, create, detail, update, destroy), Memberships (list, create, detail, update, destroy).

## Usage examples

See README.md sections "Usage examples" for Meetings and Messaging (Messages, Rooms, People, Webhooks).

## Testing and static analysis

- `composer test` – run Pest.
- `composer stan` – run PHPStan (Larastan).

Update the docs site’s "Testing" section to include both commands.
