# Laravel Webex
[![Latest Stable Version](https://poser.pugx.org/offline-agency/laravel-webex/v/stable)](https://packagist.org/packages/offline-agency/laravel-webex)
[![Total Downloads](https://img.shields.io/packagist/dt/offline-agency/laravel-webex.svg?style=flat-square)](https://packagist.org/packages/offline-agency/laravel-webex)
[![run-tests](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml/badge.svg)](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://github.styleci.io/repos/167236902/shield)](https://styleci.io/repos/167236902)
[![codecov](https://codecov.io/gh/offline-agency/laravel-webex/branch/master/graph/badge.svg?token=0BHADJQYAW)](https://codecov.io/gh/offline-agency/laravel-webex)

A simple Laravel integration with Webex.

![Laravel Cart](https://banners.beyondco.de/Laravel%20Webex.png?theme=dark&packageManager=composer+require&packageName=offline-agency%2Flaravel-webex&pattern=volcanoLamp&style=style_1&description=A+simple+Laravel+integration+with+Webex&md=1&showWatermark=0&fontSize=100px&images=video-camera)

## Installation

Install the package through [Composer](http://getcomposer.org/).

Run the Composer require command from the Terminal:

```bash
composer require offline-agency/laravel-webex
```

You should publish config file with:

```bash
php artisan vendor:publish --provider="Offlineagency\LaravelWebex\Providers\LaravelWebexServiceProvider"
```


## API coverage

We're actively working to expand this package to cover all Webex API endpoints.

✅ = implemented

🔜 = coming soon

❌ = not implemented

### Admin
- #### ❌ Admin Audit Events
- #### ❌ Events
- #### ❌ Historical Analytics
- #### ❌ Hybrid Clusters
- #### ❌ Hybrid Connectors
- #### ❌ Licenses
- #### ❌ Locations
- #### ❌ Meeting Qualities
- #### ❌ Memberships
- #### ❌ Organizations
- #### ❌ People
- #### ❌ Recording Report
- #### ❌ Recordings
- #### ❌ Report Templates
- #### ❌ Reports
- #### ❌ Resource Group Memberships
- #### ❌ Resource Group
- #### ❌ Roles
- #### ❌ Space Classifications
- #### ❌ Webex Calling Organization Settings
- #### ❌ Webex Calling Person Settings
- #### ❌ Workspace Locations
- #### ❌ Workspace Metrics
- #### ❌ Workspaces

### Calling
- #### ❌ BroadWorks Enterprises
- #### ❌ BroadWorks Subscribers
- #### ❌ Call Controls
- #### ❌ Locations
- #### ❌ People
- #### ❌ Recording Report
- #### ❌ Webex Calling Organization Settings
- #### ❌ Webex Calling Person Settings
- #### ❌ Webex Calling Voice Messaging

### Devices
- #### ❌ Device Configurations
- #### ❌ Devices
- #### ❌ Places
- #### ❌ Workspace Locations
- #### ❌ Workspace Metrics
- #### ❌ Workspace Personalization
- #### ❌ Workspaces
- #### ❌ xAPI

### Meetings
- #### ✅ Meeting Invitees
- #### ✅ Meeting Participants
- #### ❌ Meeting Preferences
- #### ❌ Meeting Qualities
- #### ❌ Meeting Transcripts
- #### ✅ Meetings
- #### ❌ People
- #### ❌ Recording Report
- #### ❌ Recordings
- #### ❌ Webhooks

### Messaging
- #### ❌ Attachment Actions
- #### ❌ Events
- #### ❌ Memberships
- #### ❌ Messages
- #### ❌ Messages with Edit
- #### ❌ People
- #### ❌ Room Tabs
- #### ❌ Rooms
- #### ❌ Team Memberships
- #### ❌ Teams
- #### ❌ Webhooks


## Documentation
You can find the documentation [here](https://docs.offlineagency.com/laravel-webex/)

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email <support@offlineagency.com> instead of using the issue
tracker.

## Credits

- [Offline Agency](https://github.com/offline-agency)
- [Giacomo Fabbian](https://github.com/Giacomo92)
- [Nicolas Sanavia](https://github.com/SanaviaNicolas)
- [All Contributors](../../contributors)

## About us

Offline Agency is a web design agency based in Padua, Italy. You'll find an overview of our
projects [on our website](https://offlineagency.it/).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
