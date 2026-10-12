# Laravel Webex

[![CI](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml?query=branch%3Amain)
[![Release](https://github.com/offline-agency/laravel-webex/actions/workflows/release.yml/badge.svg)](https://github.com/offline-agency/laravel-webex/actions/workflows/release.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/offline-agency/laravel-webex.svg)](https://packagist.org/packages/offline-agency/laravel-webex)
[![Total Downloads](https://img.shields.io/packagist/dt/offline-agency/laravel-webex.svg)](https://packagist.org/packages/offline-agency/laravel-webex)
[![PHP 8.4 | 8.5](https://img.shields.io/badge/PHP-8.4%20%7C%208.5-777bb4.svg)](#supported-platforms)
[![Laravel 12 | 13](https://img.shields.io/badge/Laravel-12%20%7C%2013-ff2d20.svg)](#supported-platforms)
[![codecov](https://codecov.io/gh/offline-agency/laravel-webex/branch/main/graph/badge.svg)](https://codecov.io/gh/offline-agency/laravel-webex)
[![License: MIT](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)

A Laravel client for the Webex REST APIs: Meetings, Messaging, Admin, Calling and Devices modules, returned as typed entities or an `Error`.

## Supported platforms

| Package | PHP | Laravel |
| --- | --- | --- |
| 2.x | 8.4, 8.5 | 12 (from 12.69.2), 13 (from 13.32) |
| 1.x | see the 1.x README | |

Policy: the package supports the Laravel majors that still receive security fixes and the PHP branches the selected Laravel majors support, starting from PHP 8.4. Laravel 11, PHP 8.3 and older are not supported by 2.x (PHP 8.3 is still maintained upstream, but outside this package's policy). Pre-release PHP or Laravel versions are never added to the supported set. Both floors and both majors run in CI.

## Installation

```bash
composer require offline-agency/laravel-webex
```

The service provider and the `LaravelWebex` facade are auto-discovered. Publish the configuration:

```bash
php artisan vendor:publish --tag=webex-config
```

and set in `.env` (never commit tokens):

| Variable | Purpose |
| --- | --- |
| `WEBEX_BEARER` | bearer token sent with every request (required) |
| `WEBEX_TIMEOUT` | request timeout in seconds (default 30) |
| `WEBEX_CLIENT_ID`, `WEBEX_CLIENT_SECRET`, `WEBEX_CLIENT_CODE`, `WEBEX_REDIRECT_URI` | OAuth integration values |
| `WEBEX_AUTH_ROUTE_ENABLED` | `false` to not register the package's `GET /auth` route |

### Authentication events

Each `LaravelWebex` instance reads the bearer token when it is created:

1. `AuthenticationRequested` is dispatched first: a listener may set `config(['webex.bearer' => ...])` here.
2. The HTTP client is built with that token and the configured timeout.
3. `SuccessfulAuthentication` is dispatched: the client is configured. It does **not** mean that Webex accepted the token; a rejected token shows up as an `Error` (HTTP 401) on the first request.

The facade resolves a singleton, so its token is fixed for the life of the process (queue workers, Octane). Create `new \Offlineagency\LaravelWebex\LaravelWebex` when the token changes.

The package also registers `GET /auth` (route name `auth`, throttled), which only dispatches `SuccessfulAuthentication`. Configure `webex.auth_route` (`enabled`, `path`, `name`) if your application uses that path or name.

## Usage

Every example below is executed by `tests/Feature/ReadmeExamplesTest.php`, which checks the HTTP method and path against the official Webex OpenAPI specification.

```php
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebexFacade as LaravelWebex;

// Meetings
$meetings = LaravelWebex::meeting()->list(['state' => 'inProgress', 'max' => 10]);
$meeting = LaravelWebex::meeting()->create('My Meeting', '2026-11-15T10:00:00Z', '2026-11-15T11:00:00Z', ['agenda' => 'Agenda']);
$invitees = LaravelWebex::meeting_invitees()->list($meetingId);

// Messages
$message = LaravelWebex::messages()->create($roomId, 'Hello!');
$message = LaravelWebex::messages()->edit($messageId, 'Updated text');
$deleted = LaravelWebex::messages()->destroy($messageId); // true or Error

// Rooms, teams, memberships
$room = LaravelWebex::rooms()->create('My Room');
$membership = LaravelWebex::memberships()->create($roomId, 'user@example.com');
$team = LaravelWebex::teams()->update($teamId, 'New Name');

// People and webhooks
$person = LaravelWebex::people()->detailPerson($personId);
$webhook = LaravelWebex::webhooks()->createWebhook('My Webhook', 'https://example.com/webhook', 'meetings', 'created');
```

### Results and errors

A method returns its entity (or a list of entities, `true` for deletions) when Webex answers with a 2xx status, and an `Error` otherwise:

```php
$message = LaravelWebex::messages()->detail($messageId);

if ($message instanceof Error) {
    report("Webex error {$message->trackingId}: {$message->message}");
}
```

| Situation | Result |
| --- | --- |
| 2xx with a JSON document | entity / list |
| 204 (or empty 2xx) on a deletion | `true` |
| 2xx without a document where an entity is expected | `Error` "Webex returned no document (HTTP 200)." |
| 2xx with malformed JSON or a JSON scalar | `Error` |
| 4xx / 5xx with a Webex error document | `Error` with `message`, `errors`, `trackingId` |
| 4xx / 5xx with another body | `Error` "Webex request failed (HTTP n)." (the body is not echoed) |
| network failure | `Error` "Connection to Webex failed: ..." |
| a bug in your code (`TypeError`, ...) | the exception is thrown, not converted |

Entity properties are filled from the fields Webex returns; a field whose value does not fit a typed property is ignored.

### Pagination

`messages()->listWithPagination()` and `rooms()->listWithPagination()` return `['items' => [...], 'nextLink' => string|null]` from Webex's `Link: rel="next"` header. The package does not follow `nextLink` for you. If you request it yourself, check that its host is `webexapis.com` before sending your bearer token to it, and stop when it repeats or a page limit is reached.

## API coverage

The implemented operations and the known gaps are listed in [WEBEX_ENDPOINT_COVERAGE.md](WEBEX_ENDPOINT_COVERAGE.md). The package does not cover every Webex API.

## Development

```bash
composer test        # Pest (no request may reach the network: Http::preventStrayRequests())
composer stan        # Larastan / PHPStan at level max, no baseline, no ignored errors
composer pint:check  # Laravel Pint
```

CI runs PHP 8.4/8.5 x Laravel 12/13, the lowest supported versions, 100 % line coverage of `src` and `Http`, Composer validation and audit, and actionlint.

## Releases

Every pull request merged into `main` is released automatically once the CI run of its merge commit has passed: one stable tag (`MAJOR.MINOR.PATCH`, no `v` prefix) and one GitHub Release with the pull request's description. The version bump comes from a `release:major|minor|patch` label or the Conventional Commit title (`feat` → minor, `!`/`BREAKING CHANGE` → major, anything else → patch). See [CONTRIBUTING.md](CONTRIBUTING.md#releases).

Upgrading from 1.x: [UPGRADE.md](UPGRADE.md). Changes: [CHANGELOG.md](CHANGELOG.md) and the [GitHub Releases](https://github.com/offline-agency/laravel-webex/releases).

## Documentation

[docs.offlineagency.com/laravel-webex](https://docs.offlineagency.com/laravel-webex/)

## Security

Please report vulnerabilities privately to <info@offlineagency.it> (the contact published on the [Offline Agency GitHub organization](https://github.com/offline-agency)), not in public issues. See [SECURITY.md](SECURITY.md).

## Credits

- [Offline Agency](https://github.com/offline-agency)
- [All contributors](https://github.com/offline-agency/laravel-webex/graphs/contributors)

## License

MIT. See [LICENSE.md](LICENSE.md).
