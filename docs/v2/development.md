# Development

```bash
composer test        # Pest (no request may reach the network: Http::preventStrayRequests())
composer stan        # Larastan / PHPStan at level max, no baseline, no ignored errors
composer pint:check  # Laravel Pint
```

CI runs PHP 8.4/8.5 x Laravel 12/13 and PHP 8.6 x Laravel 13, the lowest supported versions, 100 % line coverage of `src` and `Http`, Composer validation and audit, and actionlint.

See [CONTRIBUTING.md](../../CONTRIBUTING.md) for the contribution rules.
