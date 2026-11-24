# Laravel Webex 2.x

A Laravel client for the Webex REST APIs: Meetings, Messaging, Admin, Calling and Devices modules, returned as typed entities or an `Error`.

| Package | PHP | Laravel |
| --- | --- | --- |
| 2.1 | 8.4, 8.5, 8.6 | 12 (from 12.69.2), 13 (from 13.32) |
| 2.0 | 8.4, 8.5 | 12 (from 12.69.2), 13 (from 13.32) |

PHP 8.6 runs with Laravel 13 only: Laravel 12 cannot be installed with the test tooling that supports PHP 8.6 (Pest 5 requires Laravel 13.23 or later).

## Contents

- [Installation](installation.md): requirements, installing the package, the `.env` variables
- [Configuration](configuration.md): tokens, events, retries and the `/auth` route
- [Usage](usage.md): examples, results and errors
- [Pagination](pagination.md): lazy iteration over pages
- [API coverage](api-coverage.md): implemented operations and known gaps
- [Development](development.md): tests, static analysis and code style

Upgrading from 1.x: [UPGRADE.md](../../UPGRADE.md). Changes: [CHANGELOG.md](../../CHANGELOG.md).
