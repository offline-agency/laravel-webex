# Laravel Webex

[![Latest Stable Version](https://poser.pugx.org/offline-agency/laravel-webex/v/stable)](https://packagist.org/packages/offline-agency/laravel-webex)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Laravel](https://github.com/offline-agency/laravel-webex/actions/workflows/laravel.yml/badge.svg?branch=master)](https://github.com/offline-agency/laravel-webex/actions/workflows/laravel.yml)
[![StyleCI](https://github.styleci.io/repos/167236902/shield)](https://styleci.io/repos/167236902)
[![Total Downloads](https://img.shields.io/packagist/dt/offline-agency/laravel-webex.svg?style=flat-square)](https://packagist.org/packages/offline-agency/laravel-webex)

A simple Laravel integration with Webex.

![Laravel Cart](https://banners.beyondco.de/Laravel%20Webex.png?theme=dark&packageManager=composer+require&packageName=offline-agency%2Flaravel-webex&pattern=volcanoLamp&style=style_1&description=A+simple+Laravel+integration+with+Webex&md=1&showWatermark=0&fontSize=100px&images=video-camera)

## Installation

Install the package through [Composer](http://getcomposer.org/).

Run the Composer require command from the Terminal:

```bash
composer require offline-agency/laravel-webex
```

## Usage

```php
// Usage description here
```

## Api coverage

### Admin

#### Admin Audit Events
- [ ] GET - List Admin Audit Events

#### Events
- [ ] GET - List Events
- [ ] GET - Get Event Details

#### Historical Analytics
- [ ] GET - Historical Data related to Messaging
- [ ] GET - Historical Data related to Room Devices
- [ ] GET - Historical Data related to Meetings

#### Hybrid Clusters
- [ ] GET - List Hybrid Clusters
- [ ] GET - Get Hybrid Cluster Details

#### Hybrid Connectors
- [ ] GET - List Hybrid Connectors
- [ ] GET - Get Hybrid Connector Details

#### Licenses
- [ ] GET - List Licenses
- [ ] GET - Get License Details

#### Locations
- [ ] GET - List Locations
- [ ] GET - Get Location Details

#### Meeting Qualities
- [ ] GET - Get Meeting Qualities

#### Memberships
- [ ] GET - List Memberships
- [ ] POST - Create Membership
- [ ] GET - Get Membership Details
- [ ] PUT - Update a Membership
- [ ] DELETE - Delete a Membership

#### Organizations
- [ ] GET - List Organizations
- [ ] GET - Get Organization Details
- [ ] DELETE - Delete Organization

#### People
- [ ] GET - List People
- [ ] POST - Create a Person
- [ ] GET - Get Person Details
- [ ] PUT - Update a Person
- [ ] DELETE - Delete a Person
- [ ] GET - Get My Own Details

#### Recording Report
- [ ] GET - List of Recording Audit Report Summaries
- [ ] GET - Get Recording Audit Report Details

#### Recordings
- [ ] GET - List Recordings
- [ ] GET - Get Recording Details
- [ ] DELETE - Delete a Recording

#### Report Templates
- [ ] GET - List Report Templates

#### Reports
- [ ] GET - List Reports
- [ ] POST - Create a Report
- [ ] GET - Get Report Details
- [ ] DELETE - Delete a Report

#### Resource Group Memberships
- [ ] GET - List Resource Group Memberships
- [ ] GET - Get Resource Group Membership Details
- [ ] PUT - Update a Resource Group Membership

#### Resource Group
- [ ] GET - List Resource Groups
- [ ] GET - Get Resource Group Details

#### Roles
- [ ] GET - List Roles
- [ ] GET - Get Role Details

#### Space Classifications
- [ ] GET - List classifications

#### Webex Calling Organization Settings
- [ ] GET - Read the List of Call Pickups
- [ ] POST - Create a Call Pickup
- [ ] DELETE - Delete a Call Pickup
- [ ] GET - Get Details for a Call Pickup
- [ ] PUT - Update a Call Pickup
- [ ] GET - Get available agents from Call Pickups
- [ ] GET - Read the List of Call Queues
- [ ] POST - Create a Call Queue
- [ ] DELETE - Delete a Call Queue
- [ ] GET - Get Details for a Call Queue
- [ ] PUT - Update a Call Queue
- [ ] GET - Read the List of Call Queue Announcement Files
- [ ] DELETE - Delete a Call Queue Announcement File
- [ ] GET - Get Call Forwarding Settings for a Call Queue
- [ ] PUT - Update Call Forwarding Settings for a Call Queue
- [ ] POST - Create a Selective Call Forwarding Rule for a Call Queue
- [ ] GET - Get Call Forwarding Rule Settings for a Call Queue
- [ ] PUT - Update Call Forwarding Rule Settings for a Call Queue
- [ ] DELETE - Delete a Selective Call Forwarding Rule for a Call Queue
- [ ] GET - Read the List of Hunt Groups
- [ ] POST - Create a Hunt Group
- [ ] DELETE - Delete a Hunt Group
- [ ] GET - Get Details for a Hunt Group
- [ ] PUT - Update a Hunt Group
- [ ] GET - Get Call Forwarding Settings for a Hunt Group
- [ ] PUT - Update Call Forwarding Settings for a Hunt Group
- [ ] POST - Create a Selective Call Forwarding Rule for a Hunt Group
- [ ] GET - Get Call Forwarding Rule Settings for a Hunt Group
- [ ] PUT - Update Call Forwarding Rule Settings for a Hunt Group
- [ ] DELETE - Delete a Selective Call Forwarding Rule for a Hunt Group
- [ ] GET - Read the List of UC Manager Profiles

#### Webex Calling Person Settings
- [ ] GET - Read Person's UC Profile 
- [ ] PUT - Configure a Person's UC Profile
- [ ] GET - Read Barge In Settings for a Person
- [ ] PUT - Configure Barge In Settings for a Person
- [ ] GET - Read Forwarding Settings for a Person
- [ ] PUT - Configure Call Forwarding Settings for a Person
- [ ] GET - Read Call Intercept Settings for a Person
- [ ] PUT - Configure Call Intercept Settings for a Person
- [ ] POST - Configure Call Intercept Greeting for a Person
- [ ] GET - Read Call Recording Settings for a Person
- [ ] PUT - Configure Call Recording Settings for a Person
- [ ] GET - Read Caller ID Settings for a Person
- [ ] PUT - Configure Caller ID Settings for a Person
- [ ] GET - Read Do Not Disturb Settings for a Person
- [ ] PUT - Configure Do Not Disturb Settings for a Person
- [ ] GET - Read Voicemail Settings for a Person
- [ ] PUT - Configure Voicemail Settings for a Person
- [ ] POST - Configure Busy Voicemail Greeting for a Person
- [ ] POST - Configure No Answer Voicemail Greeting for a Person

#### Workspace Locations
- [ ] GET - List Workspace Locations
- [ ] POST - Create a Workspace Location
- [ ] GET - Get a Workspace Location Details
- [ ] PUT - Update a Workspace Location
- [ ] DELETE - Delete a Workspace Location
- [ ] GET - List Workspace Location Floors
- [ ] POST - Create a Workspace Location Floor
- [ ] GET - Get a Workspace Location Floor Details
- [ ] PUT - Update a Workspace Location Floor
- [ ] DELETE - Delete a Workspace Location Floor

#### Workspace Metrics
- [ ] GET - Workspace Metrics
- [ ] GET - Workspace Duration Metrics

#### Workspaces
- [ ] GET - List Workspaces
- [ ] POST - Create a Workspaces
- [ ] GET - Get Workspace Details
- [ ] PUT - Update a Workspace
- [ ] DELETE - Delete a Workspace

### Calling

#### BroadWorks Enterprises
- [ ] GET - List BroadWorks Enterprises
- [ ] PUT - Update Directory Sync for a BroadWorks Enterprise
- [ ] POST - Trigger Directory Sync for an Enterprise
- [ ] GET - Get Directory Sync Status for an Enterprise

#### BroadWorks Subscribers
- [ ] GET - List BroadWorks Subscribers
- [ ] POST - Provision a BroadWorks Subscriber
- [ ] GET - Get a BroadWorks Subscriber
- [ ] PUT - Update a BroadWorks Subscriber
- [ ] DELETE - Remove a BroadWorks Subscriber

#### Call Controls
- [ ] POST - Dial
- [ ] POST - Answer
- [ ] POST - Reject
- [ ] POST - Hangup
- [ ] POST - Hold
- [ ] POST - Resume
- [ ] POST - Divert
- [ ] POST - Transfer
- [ ] POST - Park
- [ ] POST - Retrieve
- [ ] POST - Start Recording
- [ ] POST - Stop Recording
- [ ] POST - Pause Recording
- [ ] POST - Resume Recording
- [ ] POST - Transmit DTMF
- [ ] POST - Push
- [ ] POST - Pickup
- [ ] POST - Barge In
- [ ] GET - List Calls
- [ ] GET - Get Call Details
- [ ] GET - List Call History

#### Locations
- [ ] GET - List Locations
- [ ] GET - Get Location Details

#### People
- [ ] GET - List People
- [ ] POST - Create a Person
- [ ] GET - Get Person Details
- [ ] PUT - Update a Person
- [ ] DELETE - Delete a Person
- [ ] GET - Get My Own Details

#### Recording Report
- [ ] GET - List of Recording Audit Report Summaries
- [ ] GET - Get Recording Audit Report Details

#### Webex Calling Organization Settings
- [ ] GET - Read the List of Call Pickups
- [ ] POST - Create a Call Pickup
- [ ] DELETE - Delete a Call Pickup
- [ ] GET - Get Details for a Call Pickup
- [ ] PUT - Update a Call Pickup
- [ ] GET - Get available agents from Call Pickups
- [ ] GET - Read the List of Call Queues
- [ ] POST - Create a Call Queue
- [ ] DELETE - Delete a Call Queue
- [ ] GET - Get Details for a Call Queue
- [ ] PUT - Update a Call Queue
- [ ] GET - Read the List of Call Queue Announcement Files
- [ ] DELETE - Delete a Call Queue Announcement File
- [ ] GET - Get Call Forwarding Settings for a Call Queue
- [ ] PUT - Update Call Forwarding Settings for a Call Queue
- [ ] POST - Create a Selective Call Forwarding Rule for a Call Queue
- [ ] GET - Get Call Forwarding Rule Settings for a Call Queue
- [ ] PUT - Update Call Forwarding Rule Settings for a Call Queue
- [ ] DELETE - Delete a Selective Call Forwarding Rule for a Call Queue
- [ ] GET - Read the List of Hunt Groups
- [ ] POST - Create a Hunt Group
- [ ] DELETE - Delete a Hunt Group
- [ ] GET - Get Details for a Hunt Group
- [ ] PUT - Update a Hunt Group
- [ ] GET - Get Call Forwarding Settings for a Hunt Group
- [ ] PUT - Update Call Forwarding Settings for a Hunt Group
- [ ] POST - Create a Selective Call Forwarding Rule for a Hunt Group
- [ ] GET - Get Call Forwarding Rule Settings for a Hunt Group
- [ ] PUT - Update Call Forwarding Rule Settings for a Hunt Group
- [ ] DELETE - Delete a Selective Call Forwarding Rule for a Hunt Group
- [ ] GET - Read the List of UC Manager Profiles

#### Webex Calling Person Settings
- [ ] GET - Read Person's UC Profile
- [ ] PUT - Configure a Person's UC Profile
- [ ] GET - Read Barge In Settings for a Person
- [ ] PUT - Configure Barge In Settings for a Person
- [ ] GET - Read Forwarding Settings for a Person
- [ ] PUT - Configure Call Forwarding Settings for a Person
- [ ] GET - Read Call Intercept Settings for a Person
- [ ] PUT - Configure Call Intercept Settings for a Person
- [ ] POST - Configure Call Intercept Greeting for a Person
- [ ] GET - Read Call Recording Settings for a Person
- [ ] PUT - Configure Call Recording Settings for a Person
- [ ] GET - Read Caller ID Settings for a Person
- [ ] PUT - Configure Caller ID Settings for a Person
- [ ] GET - Read Do Not Disturb Settings for a Person
- [ ] PUT - Configure Do Not Disturb Settings for a Person
- [ ] GET - Read Voicemail Settings for a Person
- [ ] PUT - Configure Voicemail Settings for a Person
- [ ] POST - Configure Busy Voicemail Greeting for a Person
- [ ] POST - Configure No Answer Voicemail Greeting for a Person

#### Webex Calling Voice Messaging
- [ ] GET - Get Message Summary
- [ ] GET - List Messages
- [ ] DELETE - Delete Messages
- [ ] POST - Mark As Read
- [ ] POST - Mark As Unread

### Devices

#### Device Configurations 
- [ ] GET - List Device Configurations for device
- [ ] PATCH - Update Device Configurations

#### Devices
- [ ] GET - List Devices
- [ ] GET - Get Device Details
- [ ] DELETE - Delete a Device
- [ ] PATCH - Modify Device Tags
- [ ] POST - Create a Device Activation Code

#### Places
- [ ] GET - List Places
- [ ] POST - Create a Place
- [ ] GET - Get Place Details
- [ ] PUT - Update a Place
- [ ] DELETE - Delete a Place

#### Workspace Locations
- [ ] GET - List Workspace Locations
- [ ] POST - Create a Workspace Location
- [ ] GET - Get a Workspace Location Details
- [ ] PUT - Update a Workspace Location
- [ ] DELETE - Delete a Workspace Location
- [ ] GET - List Workspace Location Floors
- [ ] POST - Create a Workspace Location Floor
- [ ] GET - Get a Workspace Location Floor Details
- [ ] PUT - Update a Workspace Location Floor
- [ ] DELETE - Delete a Workspace Location Floor

#### Workspace Metrics
- [ ] GET - Workspace Metrics 
- [ ] GET - Workspace Duration Metrics 

#### Workspace Personalization
- [ ] POST - Personalize a Workspace
- [ ] GET - Get Personalization Task

#### Workspaces
- [ ] GET - List Workspaces
- [ ] POST - Create a Workspace
- [ ] GET - Get Workspace Details
- [ ] PUT - Update a Workspace
- [ ] DELETE - Delete a Workspace

#### xAPI
- [ ] GET - Query Status
- [ ] POST - Execute Command

### Meetings

#### Meeting Invitees
- [ ] GET - List Meeting Invitees
- [ ] POST - Create a Meeting Invitee
- [ ] POST - Create Meeting Invitees
- [ ] GET - Get a Meeting Invitee
- [ ] PUT - Update a Meeting Invitee
- [ ] DELETE - Delete a Meeting Invitee

#### Meeting Participants
- [ ] GET - List Meeting Participants
- [ ] GET - Get Meeting Participant Details
- [ ] GET - Update a Participant
- [ ] GET - Admit Participants

#### Meeting Preferences
- [ ] GET - Get Meeting Preference Details
- [ ] GET - Get Personal Meeting Room Options
- [ ] PUT - Update Personal Meeting Room Options
- [ ] GET - Get Audio Options
- [ ] PUT - Update Audio Options
- [ ] GET - Get Video Options
- [ ] PUT - Update Video Options
- [ ] GET - Get Scheduling Options
- [ ] PUT - Update Scheduling Options
- [ ] GET - Get Site List
- [ ] PUT - Update Default Site

#### Meeting Qualities
- [ ] GET - Get Meeting Qualities

#### Meeting Transcripts
- [ ] GET - List Meeting Transcripts
- [ ] GET - Download a meeting transcript
- [ ] GET - List Snippets of a Meeting Transcript
- [ ] GET - Get a Transcript Snippet
- [ ] PUT - Update a Transcript Snippet

#### Meetings
- [ ] GET - List Meetings of a Meeting Series
- [ ] POST - Create a Meeting
- [X] GET - Get a Meeting 
- [ ] GET - List Meetings
- [ ] PUT - Update a Meeting
- [ ] DELETE - Delete a Meeting
- [ ] GET - Get Meeting Control Status
- [ ] PUT - Update Meeting Control Status
- [ ] GET - List Meeting Session Types
- [ ] GET - Get a Meeting Session Type
- [ ] GET - Get registration form for a meeting
- [ ] POST - Register a Meeting Registrant
- [ ] GET - Get a meeting registrant's detail information
- [ ] GET - List Meeting Registrant
- [ ] POST - Batch Update Meeting Registrants status
- [ ] DELETE - Delete a Meeting Registrant

#### People
- [ ] GET - List People
- [ ] POST - Create a Person
- [ ] GET - Get Person Details
- [ ] PUT - Update a Person
- [ ] DELETE - Delete a Person
- [ ] GET - Get My Own Details

#### Recording Report
- [ ] GET - List of Recording Audit Report Summaries
- [ ] GET - Get Recording Audit Report Details

#### Recordings
- [ ] GET - List Recordings
- [ ] GET - Get Recording Details
- [ ] DELETE - Delete a Recording

#### Webhooks
- [ ] GET - List Webhooks
- [ ] POST - Create a Webhook
- [ ] GET - Get Webhook Details
- [ ] PUT - Update a Webhook
- [ ] DELETE - Delete a Webhook

### Messaging

#### Attachment Actions
- [ ] GET - Create an Attachment Action
- [ ] GET - Get Attachment Action Details

#### Events
- [ ] GET - List Events
- [ ] GET - Get Event Details

#### Memberships
- [ ] GET - List Memberships
- [ ] POST - Create Membership
- [ ] GET - Get Membership Details
- [ ] PUT - Update a Membership
- [ ] DELETE - Delete a Membership

#### Messages
- [ ] GET - List Messages
- [ ] GET - List Direct Messages
- [ ] POST - Create a Message
- [ ] PUT - Edit a Message
- [ ] GET - Get Message Details
- [ ] DELETE - Delete a Message

#### Messages with Edit
- [ ] GET - List Messages
- [ ] GET - List Direct Messages
- [ ] POST - Create a Message
- [ ] PUT - Edit a Message
- [ ] GET - Get Message Details
- [ ] DELETE - Delete a Message

#### People
- [ ] GET - List People
- [ ] POST - Create a Person
- [ ] GET - Get Person Details
- [ ] PUT - Update a Person
- [ ] DELETE - Delete a Person
- [ ] GET - Get My Own Details

#### Room Tabs
- [ ] GET - List Room Tabs
- [ ] POST - Create a Room Tab
- [ ] GET - Get Room Tab Details
- [ ] PUT - Update a Room Tab
- [ ] DELETE - Delete a Room Tab

#### Rooms
- [X] GET - List Rooms
- [ ] POST - Create a Room
- [ ] GET - Get Room Details
- [ ] GET - Get Room Meeting Details
- [ ] PUT - Update a Room 
- [ ] DELETE - Delete a Room

#### Team Memberships
- [ ] GET - List Team Memberships
- [ ] POST - Create a Team Membership
- [ ] GET - Get Team Membership Details
- [ ] PUT - Update a Team Membership
- [ ] DELETE - Delete a Team Membership

#### Teams
- [ ] GET - List Teams
- [ ] GET - Create a Team
- [ ] GET - Get Team Details
- [ ] GET - Update a Team
- [ ] GET - Delete a Team

#### Webhooks
- [ ] GET - List Webhooks
- [ ] POST - Create a Webhook
- [ ] GET - Get Webhook Details
- [ ] PUT - Update a Webhook
- [ ] DELETE - Delete a Webhook

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email <support@offlineagency.com> instead of using the issue tracker.

## Credits

- [Offline Agency](https://github.com/offline-agency)
- [Giacomo Fabbian](https://github.com/Giacomo92)
- [Nicolas Sanavia](https://github.com/SanaviaNicolas)
- [All Contributors](../../contributors)

## About us

Offline Agency is a web design agency based in Padua, Italy. You'll find an overview of our projects [on our website](https://offlineagency.it/).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
