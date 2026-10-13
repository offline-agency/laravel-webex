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
| `WEBEX_AUTH_ROUTE_ENABLED` | `true` to register the package's `GET /auth` route (off by default) |

### Tokens

The bearer token is resolved **for every request**, in this order:

1. a token bound to the client: `LaravelWebex::forToken($token)->messages()->...` (one client per Webex account);
2. the `webex.token_resolver` class, a `Offlineagency\LaravelWebex\Contracts\TokenResolver` whose `token()` is called before each request (refresh OAuth tokens there; safe in queue workers and Octane);
3. `config('webex.bearer')`.

### Events

When a client is created, `AuthenticationRequested` is dispatched (a listener may set the token), then `WebexClientConfigured`. Neither means that Webex accepted the token: a rejected token comes back as an `Error` with `status` 401. `SuccessfulAuthentication` is still dispatched after `WebexClientConfigured` but is deprecated.

### Retries

`GET`, `PUT` and `DELETE` are retried after a network failure, a `429` or a `503`, waiting for `Retry-After` (seconds or HTTP date, capped by `webex.retry.max_delay_seconds`, default 30) or an exponential delay; `webex.retry.times` is the number of attempts (default 3, `1` disables retries). `POST` and `PATCH` are never retried. When Webex still answers 429, the `Error` carries `status` and `retryAfter`.

### The /auth route

`GET /auth` (route name `auth`) is registered only when `WEBEX_AUTH_ROUTE_ENABLED=true`; it dispatches `WebexClientConfigured`. Change its path and name with `webex.auth_route`.

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
| network failure (after the retries) | `Error` "Connection to Webex failed: ..." |
| a bug in your code (`TypeError`, ...) | the exception is thrown, not converted |

`Error` exposes `message`, `errors`, `trackingId`, `status` (HTTP status, `null` for network failures) and `retryAfter`.

Entity properties are typed from the official Webex OpenAPI schemas where every response agrees on a primitive type (`?string`, `?int`, `int|float|null`, `?bool`, `?array`); the others are `mixed`. A field whose value does not fit the declared type is ignored (the property stays `null`).

### Pagination

`cursor()` iterates every page lazily (a `LazyCollection`), so `take(50)` only reads the pages it needs:

```php
foreach (LaravelWebex::messages()->cursor($roomId, ['max' => 100]) as $message) {
    // ...
}

$rooms = LaravelWebex::rooms()->cursor()->take(50)->all();
```

The next page is the `Link: rel="next"` URL, followed only on the scheme, host and port of `webex.base_url`, so your token is never sent to another host. A failed page throws `WebexRequestException` (its `error` property is the `Error`); a repeated link or more than `$maxPages` pages (default 100) throws `WebexPaginationException`. `listWithPagination()` still returns one page with its `nextLink`.

## API coverage

The implemented operations and the known gaps are listed in [WEBEX_ENDPOINT_COVERAGE.md](WEBEX_ENDPOINT_COVERAGE.md). The package does not cover every Webex API. Every public endpoint method is checked by `tests/Contract` against the official [Webex OpenAPI specification](https://github.com/webex/webex-openapi-specs): each request it sends must exist in the API.

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
