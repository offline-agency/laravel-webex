# Changelog

All notable changes to `laravel-webex` will be documented in this file.

## Unreleased

### Added

- **PHP 8.4+ and Laravel 11+ support** – Minimum PHP is now 8.4; Laravel 11 and 12 are supported.
- **Config publish tag** – You can publish config with `php artisan vendor:publish --tag=webex-config` in addition to the provider.
- **Pagination helper** – List endpoints can use `getWithLink()` in API classes; Messages and Rooms expose `listWithPagination()` returning `['items' => [...], 'nextLink' => string|null]` (Webex `Link: rel="next"` header).
- **Messaging API** – Rooms (list, create, detail, update, destroy, meetingDetails), Teams (list, create, detail, update, destroy), Memberships (list, create, detail, update, destroy).
- **Messages API** – create, detail, edit, destroy; `listWithPagination()` for paginated lists.
- **Exposed existing modules** – All Meeting-related API classes are now exposed on the facade: `webhooks()`, `people()`, `recordings()`, `recording_report()`, `meeting_preferences()`, `meeting_transcripts()`, `meeting_chats()`, `meeting_qualities()`, `meeting_polls()`, `meeting_q_and_a()`, `meeting_closed_captions()`, `meeting_messages()`, `meetings_summary_report()`, `session_types()`, `tracking_codes()`, `video_mesh()`.
- **Larastan (PHPStan)** – Dev dependency and `phpstan.neon`; run with `composer stan`. CI runs static analysis.
- **Tests** – New tests for Messages (list error, create, detail, destroy, listWithPagination with Link header), Webhooks (list, list error), People (list, list error), Rooms (list, list error). Existing Meeting tests kept; missing fake response methods added.
- **Strict typing and docblocks** – AbstractApi and key classes have parameter/return types and phpdoc for Larastan.

### Fixed

- **AbstractApi** – Response body is now decoded with `$response->body()` and `json_decode()` (Laravel Http returns a Response object, not a string). DELETE builds URL with query string only when non-empty; no second parameter passed to the HTTP client.
- **People::destroyPerson** – Corrected path from `meetingTranscripts/{id}` to `people/{id}`.
- **MeetingChats** – Fixed `destroyChats` URL (missing slash before meeting id) and query param key (`meetingId` instead of `meeting_Id`).
- **MeetingTranscripts::listSnippetsOfTranscript** – Fixed URL (missing slash and `/snippets` segment).
- **MeetingParticipant::admit** – Fixed `data()` second parameter to a list of field names.
- **AuthController** – Package `Http` namespace is now autoloaded (`Offlineagency\LaravelWebex\Http` → `Http/`). Feature test uses `/auth` URL instead of `route('auth')` where route helper was unavailable.
- **LaravelWebexFacadeTest** – Added assertion to avoid risky test.

### Changed

- **Breaking** – PHP 7.1–8.3 and Laravel 5–9 are no longer supported. Use PHP 8.4+ and Laravel 11+.
- **Breaking** – Messages::delete() renamed to Messages::destroy() to avoid conflict with AbstractApi::delete().
- **GitHub Actions** – Matrix updated to PHP 8.4 and Laravel 11/12; added PHPStan step; updated checkout and cache actions.
- **Composer** – Removed `guzzlehttp/guzzle` and `doctrine/dbal` from direct requirements; added `larastan/larastan`. Testbench ^9|^10, PHPUnit ^10|^11.

## 1.0.0 - 201X-XX-XX

- Initial release
