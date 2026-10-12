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
- **Static analysis at max level** – PHPStan/Larastan now runs at `level: max` over `src`, `Http` and `tests` with no ignored errors. All API methods document parameter and return types (e.g. `MeetingsEntity|Error`); raw responses are described by the `ApiResponse` object shape (`object{success: bool, data: array|object|null}`); untyped entity properties are declared `mixed`.
- **100% test coverage** – CI fails below 100% line coverage; `Http/` is now part of the coverage report.

### Fixed

- **Null `$additional_data`** – Passing `null` (allowed by the `?array` signatures) no longer throws a `TypeError` in `AbstractApi::data()`, `AbstractApi::value()` (e.g. `MeetingParticipant::list()`) and `Teams::create()`.
- **Non-object JSON bodies** – A response body that decodes to a JSON scalar is treated as no data instead of throwing a `TypeError` when building an entity or `Error`; non-object entries in `items` are skipped.
- **Config values** – A non-string `webex.base_url` / `webex.bearer` falls back to an empty string instead of throwing a `TypeError`; a non-numeric `webex.timeout` falls back to 30 seconds.
- **Return type docs** – `CallControls::dial()` is documented as `CallEntity|Error`; `Xapi::command()`/`status()`, `Devices::createActivationCode()`, `DeviceConfigurations::update()` and `Rooms::meetingDetails()` are documented as possibly returning an array or `null` (e.g. on 204).
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
- **GitHub Actions** – Matrix updated to PHP 8.4 and Laravel 11/12; added PHPStan step; updated checkout and cache actions. CI now runs on pushes to `master` (previously `main`, which does not exist).
- **Code style** – StyleCI replaced by Laravel Pint (`pint.json`, `composer pint`, `pint --test` in CI).
- **Composer** – Removed `guzzlehttp/guzzle` and `doctrine/dbal` from direct requirements; added `larastan/larastan`. Testbench ^9|^10, PHPUnit ^10|^11.

## 1.0.0 - 201X-XX-XX

- Initial release
