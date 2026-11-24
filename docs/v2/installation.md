# Installation

## Requirements

- PHP 8.4, 8.5 or 8.6 (`~8.4.0 || ~8.5.0 || ~8.6.0`)
- Laravel 12 (from 12.69.2) or 13 (from 13.32); on PHP 8.6 use Laravel 13

Laravel 11, PHP 8.3 and older are not supported by 2.x. See [UPGRADE.md](../../UPGRADE.md) for 1.x users.

## Install

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
