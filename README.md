# Laravel Webex

[![Latest Release](https://img.shields.io/github/v/release/offline-agency/laravel-webex?sort=semver)](https://github.com/offline-agency/laravel-webex/releases)
[![Packagist Version](https://img.shields.io/packagist/v/offline-agency/laravel-webex)](https://packagist.org/packages/offline-agency/laravel-webex)
[![Total Downloads](https://img.shields.io/packagist/dt/offline-agency/laravel-webex)](https://packagist.org/packages/offline-agency/laravel-webex)
[![PHP Version](https://img.shields.io/packagist/dependency-v/offline-agency/laravel-webex/php)](https://packagist.org/packages/offline-agency/laravel-webex)
[![Tests](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml)
[![Release workflow](https://github.com/offline-agency/laravel-webex/actions/workflows/release.yml/badge.svg)](https://github.com/offline-agency/laravel-webex/actions/workflows/release.yml)
[![codecov](https://codecov.io/gh/offline-agency/laravel-webex/branch/main/graph/badge.svg)](https://codecov.io/gh/offline-agency/laravel-webex)
[![License](https://img.shields.io/github/license/offline-agency/laravel-webex)](LICENSE.md)

A Laravel client for the Webex REST APIs: Meetings, Messaging, Admin, Calling and Devices modules, returned as typed entities or an `Error`.

## Compatibility

| Version | PHP | Framework |
| --- | --- | --- |
| 2.1 | `~8.4.0 \|\| ~8.5.0 \|\| ~8.6.0` | `laravel/framework` `^12.69.2 \|\| ^13.32` |
| 2.0 | `~8.4.0 \|\| ~8.5.0` | `laravel/framework` `^12.69.2 \|\| ^13.32` |
| 1.x | `>=7.1` | `illuminate/support` `^5.0` to `^9.0`; `illuminate/container`, `illuminate/http` `^6.0` to `^9.0` (1.1.0) |

Policy: the package supports the Laravel majors that still receive security fixes and the PHP branches the selected Laravel majors support, starting from PHP 8.4. Laravel 11, PHP 8.3 and older are not supported by 2.x (PHP 8.3 is still maintained upstream, but outside this package's policy). Pre-release PHP or Laravel versions are never added to the supported set. Both floors and both majors run in CI. PHP 8.6 is tested with Laravel 13 only: the test tooling that supports PHP 8.6 (Pest 5) requires Laravel 13.23 or later, so Laravel 12 is supported on PHP 8.4 and 8.5.

## Installation

```bash
composer require offline-agency/laravel-webex
php artisan vendor:publish --tag=webex-config
```

The service provider and the `LaravelWebex` facade are auto-discovered. Set `WEBEX_BEARER` in `.env` (never commit tokens). The other variables, tokens, events, retries and the `/auth` route are in the [configuration](docs/v2/configuration.md) and [installation](docs/v2/installation.md) pages.

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

Results and errors, pagination and the full list of operations are in the [documentation](docs/v2/index.md).

## Documentation

- [Documentation by version](docs/README.md), current version: [2.x](docs/v2/index.md)
- [docs.offlineagency.com/laravel-webex](https://docs.offlineagency.com/laravel-webex/)
- API coverage: [WEBEX_ENDPOINT_COVERAGE.md](WEBEX_ENDPOINT_COVERAGE.md)

## Development

```bash
composer test        # Pest (no request may reach the network: Http::preventStrayRequests())
composer stan        # Larastan / PHPStan at level max, no baseline, no ignored errors
composer pint:check  # Laravel Pint
```

CI runs PHP 8.4/8.5 x Laravel 12/13 and PHP 8.6 x Laravel 13, the lowest supported versions, 100 % line coverage of `src` and `Http`, Composer validation and audit, and actionlint. See [CONTRIBUTING.md](CONTRIBUTING.md).

## Releases

Every pull request merged into `main` is released automatically once the CI run of its merge commit has passed: one stable tag (`MAJOR.MINOR.PATCH`, no `v` prefix) and one GitHub Release with the pull request's description. The version bump comes from a `release:major|minor|patch` label or the Conventional Commit title (`feat` → minor, `!`/`BREAKING CHANGE` → major, anything else → patch). See [CONTRIBUTING.md](CONTRIBUTING.md#releases).

Upgrading from 1.x: [UPGRADE.md](UPGRADE.md). Changes: [CHANGELOG.md](CHANGELOG.md) and the [GitHub Releases](https://github.com/offline-agency/laravel-webex/releases).

## Security

Please report vulnerabilities privately to <info@offlineagency.it> (the contact published on the [Offline Agency GitHub organization](https://github.com/offline-agency)), not in public issues. See [SECURITY.md](SECURITY.md).

## Credits

- [Offline Agency](https://github.com/offline-agency)
- [All contributors](https://github.com/offline-agency/laravel-webex/graphs/contributors)

## License

MIT. See [LICENSE.md](LICENSE.md).
