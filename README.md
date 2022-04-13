# Laravel Webex

[![Latest Stable Version](https://poser.pugx.org/offline-agency/laravel-webex/v/stable)](https://packagist.org/packages/offline-agency/laravel-webex)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![run-tests](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml/badge.svg)](https://github.com/offline-agency/laravel-webex/actions/workflows/main.yml)
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

You should publish config file with:

```bash
php artisan vendor:publish --tag="webex-config"
```

## Usage

Each callback accept a number of parameters equals to the sum of the required parameters +1 that is $additional_data
that accept all optional parameters.

Package provide two events to intercept the start and the end of the authentication flow.

## Examples

### Meetings

``` php
// all 
$laravel_webex = new LaravelWebex($bearer);
$meetings_list = $laravel_webex->meeting()->list();

// all filtered by state
$laravel_webex = new LaravelWebex($bearer);
$meetings_list = $laravel_webex->meeting()->list([
    'state' => $state
]);

// detail
$laravel_webex = new LaravelWebex($bearer);
$meeting_detail = $laravel_webex->meeting()->detail($meeting_id);

// detail filtered by current 
$laravel_webex = new LaravelWebex($bearer);
$meeting_detail = $laravel_webex->meeting()->detail($meeting_id, [
    'current' => $current
]);

// create
$laravel_webex = new LaravelWebex($bearer);
$new_meeting = $laravel_webex->meeting()->create($title, $start, $end, [
    'agenda' => $agenda,
    'enabledAutoRecordMeeting' => $enabledAutoRecordMeeting
]);

// update
$laravel_webex = new LaravelWebex($bearer);
$updated_meeting = $laravel_webex->meeting()->update($id, $title, $pasword, $start, $end, [
    'agenda' => $agenda,
    'enabledAutoRecordMeeting' => $enabledAutoRecordMeeting
]);

// delete 
$laravel_webex = new LaravelWebex($bearer);
$delete_response = $laravel_webex->meeting()->destroy('fake_id');
```

### Meetings participants

``` php 
$laravel_webex = new LaravelWebex($bearer);
$meeting_participants_list = $laravel_webex->meeting_participants()->list($meeting_id);
```

## Api coverage

### Admin

#### Admin Audit Events

- [ ] List Admin Audit Events [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Events

- [ ] List Events [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Event Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Historical Analytics

- [ ] Historical Data related to
  Messaging [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Historical Data related to Room
  Devices [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Historical Data related to
  Meetings [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Hybrid Clusters

- [ ] List Hybrid Clusters [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Hybrid Cluster Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Hybrid Connectors

- [ ] List Hybrid Connectors [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Hybrid Connector
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Licenses

- [ ] List Licenses [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get License Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Locations

- [ ] List Locations [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Location Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Meeting Qualities

- [ ] Get Meeting Qualities [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Memberships

- [ ] List Memberships [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create Membership [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Membership Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Membership [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Membership [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Organizations

- [ ] List Organizations [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Organization Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete Organization [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### People

- [ ] List People [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Person Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Person [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get My Own Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Recording Report

- [ ] List of Recording Audit Report
  Summaries [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Recording Audit Report
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Recordings

- [ ] List Recordings [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Recording Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Recording [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Report Templates

- [ ] List Report Templates [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Reports

- [ ] List Reports [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Report [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Report Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Report [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Resource Group Memberships

- [ ] List Resource Group
  Memberships [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Resource Group Membership
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Resource Group
  Membership [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()

#### Resource Group

- [ ] List Resource Groups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Resource Group Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Roles

- [ ] List Roles [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Role Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Space Classifications

- [ ] List classifications [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Webex Calling Organization Settings

- [ ] Read the List of Call
  Pickups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Call Pickup [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Call Pickup [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Details for a Call
  Pickup [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Call Pickup [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get available agents from Call
  Pickups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Read the List of Call
  Queues [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Call Queue [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Call Queue [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Details for a Call
  Queue [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Call Queue [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read the List of Call Queue Announcement
  Files [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Call Queue Announcement
  File [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Call Forwarding Settings for a Call
  Queue [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Settings for a Call
  Queue [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Create a Selective Call Forwarding Rule for a Call
  Queue [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Call Forwarding Rule Settings for a Call
  Queue [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Rule Settings for a Call
  Queue [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Selective Call Forwarding Rule for a Call
  Queue [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Read the List of Hunt
  Groups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Hunt Group [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Hunt Group [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Details for a Hunt
  Group [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Hunt Group [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Call Forwarding Settings for a Hunt
  Group [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Settings for a Hunt
  Group [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Create a Selective Call Forwarding Rule for a Hunt
  Group [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Call Forwarding Rule Settings for a Hunt
  Group [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Rule Settings for a Hunt
  Group [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Selective Call Forwarding Rule for a Hunt
  Group [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Read the List of UC Manager
  Profiles [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Webex Calling Person Settings

- [ ] Read Person's UC Profile [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure a Person's UC
  Profile [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Barge In Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Barge In Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Forwarding Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Call Forwarding Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Call Intercept Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Call Intercept Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Configure Call Intercept Greeting for a
  Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Read Call Recording Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Call Recording Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Caller ID Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Caller ID Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Do Not Disturb Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Do Not Disturb Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Voicemail Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Voicemail Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Configure Busy Voicemail Greeting for a
  Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Configure No Answer Voicemail Greeting for a
  Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()

#### Workspace Locations

- [ ] List Workspace Locations [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Workspace
  Location [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a Workspace Location
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Workspace Location [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Workspace
  Location [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] List Workspace Location
  Floors [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Workspace Location
  Floor [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a Workspace Location Floor
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Workspace Location
  Floor [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Workspace Location
  Floor [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Workspace Metrics

- [ ] Workspace Metrics [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Workspace Duration Metrics [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Workspaces

- [ ] List Workspaces [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Workspaces [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Workspace Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Workspace [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Workspace [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

### Calling

#### BroadWorks Enterprises

- [ ] List BroadWorks Enterprises [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Directory Sync for a BroadWorks
  Enterprise [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Trigger Directory Sync for an
  Enterprise [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Directory Sync Status for an
  Enterprise [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### BroadWorks Subscribers

- [ ] List BroadWorks Subscribers [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Provision a BroadWorks
  Subscriber [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a BroadWorks Subscriber [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a BroadWorks
  Subscriber [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Remove a BroadWorks
  Subscriber [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Call Controls

- [ ] Dial [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Answer [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Reject [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Hangup [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Hold [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Resume [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Divert [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Transfer [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Park [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Retrieve [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Start Recording [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Stop Recording [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Pause Recording [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Resume Recording [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Transmit DTMF [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Push [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Pickup [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Barge In [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] List Calls [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Call Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] List Call History [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Locations

- [ ] List Locations [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Location Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### People

- [ ] List People [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Person Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Person [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get My Own Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Recording Report

- [ ] List of Recording Audit Report
  Summaries [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Recording Audit Report
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Webex Calling Organization Settings

- [ ] Read the List of Call
  Pickups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Call Pickup [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Call Pickup [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Details for a Call
  Pickup [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Call Pickup [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get available agents from Call
  Pickups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Read the List of Call
  Queues [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Call Queue [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Call Queue [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Details for a Call
  Queue [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Call Queue [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read the List of Call Queue Announcement
  Files [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Call Queue Announcement
  File [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Call Forwarding Settings for a Call
  Queue [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Settings for a Call
  Queue [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Create a Selective Call Forwarding Rule for a Call
  Queue [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Call Forwarding Rule Settings for a Call
  Queue [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Rule Settings for a Call
  Queue [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Selective Call Forwarding Rule for a Call
  Queue [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Read the List of Hunt
  Groups [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Hunt Group [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Hunt Group [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Details for a Hunt
  Group [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Hunt Group [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Call Forwarding Settings for a Hunt
  Group [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Settings for a Hunt
  Group [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Create a Selective Call Forwarding Rule for a Hunt
  Group [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Call Forwarding Rule Settings for a Hunt
  Group [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Call Forwarding Rule Settings for a Hunt
  Group [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Selective Call Forwarding Rule for a Hunt
  Group [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Read the List of UC Manager
  Profiles [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Webex Calling Person Settings

- [ ] Read Person's UC Profile [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure a Person's UC
  Profile [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Barge In Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Barge In Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Forwarding Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Call Forwarding Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Call Intercept Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Call Intercept Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Configure Call Intercept Greeting for a
  Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Read Call Recording Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Call Recording Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Caller ID Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Caller ID Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Do Not Disturb Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Do Not Disturb Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Read Voicemail Settings for a
  Person [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Configure Voicemail Settings for a
  Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Configure Busy Voicemail Greeting for a
  Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Configure No Answer Voicemail Greeting for a
  Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()

#### Webex Calling Voice Messaging

- [ ] Get Message Summary [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] List Messages [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete Messages [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Mark As Read [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Mark As Unread [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()

### Devices

#### Device Configurations

- [ ] List Device Configurations for
  device [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] PATCH - Update Device Configurations

#### Devices

- [ ] List Devices [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Device Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Device [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] PATCH - Modify Device Tags
- [ ] Create a Device Activation
  Code [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()

#### Places

- [ ] List Places [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Place [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Place Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Place [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Place [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Workspace Locations

- [ ] List Workspace Locations [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Workspace
  Location [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a Workspace Location
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Workspace Location [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Workspace
  Location [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] List Workspace Location
  Floors [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Workspace Location
  Floor [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a Workspace Location Floor
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Workspace Location
  Floor [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Workspace Location
  Floor [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Workspace Metrics

- [ ] Workspace Metrics [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Workspace Duration Metrics [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Workspace Personalization

- [ ] Personalize a Workspace [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Personalization Task [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Workspaces

- [ ] List Workspaces [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Workspace [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Workspace Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Workspace [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Workspace [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### xAPI

- [ ] Query Status [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Execute Command [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()

### Meetings

#### Meeting Invitees

- [ ] List Meeting Invitees [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Meeting Invitee [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Create Meeting Invitees [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a Meeting Invitee [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Meeting Invitee [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Meeting
  Invitee [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Meeting Participants

- [X] List Meeting Participants [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Meeting Participant
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Participant [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Admit Participants [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Meeting Preferences

- [ ] Get Meeting Preference
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Personal Meeting Room
  Options [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Personal Meeting Room
  Options [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Audio Options [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Audio Options [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Video Options [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Video Options [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Scheduling Options [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Scheduling Options [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Site List [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Default Site [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()

#### Meeting Qualities

- [ ] Get Meeting Qualities [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Meeting Transcripts

- [ ] List Meeting Transcripts [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Download a meeting
  transcript [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] List Snippets of a Meeting
  Transcript [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get a Transcript Snippet [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Transcript Snippet [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()

#### Meetings

- [ ] List Meetings of a Meeting
  Series [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [X] Create a Meeting [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [X] Get a Meeting [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [X] List Meetings [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [X] Update a Meeting [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [X] Delete a Meeting [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get Meeting Control Status [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update Meeting Control
  Status [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] List Meeting Session Types [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get a Meeting Session Type [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get registration form for a
  meeting [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Register a Meeting
  Registrant [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get a meeting registrant's detail
  information [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] List Meeting Registrant [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Batch Update Meeting Registrants
  status [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Delete a Meeting
  Registrant [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### People

- [ ] List People [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Person Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Person [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get My Own Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Recording Report

- [ ] List of Recording Audit Report
  Summaries [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Recording Audit Report
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Recordings

- [ ] List Recordings [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Recording Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Recording [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Webhooks

- [ ] List Webhooks [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Webhook [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Webhook Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Webhook [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Webhook [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

### Messaging

#### Attachment Actions

- [ ] Create an Attachment Action [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Attachment Action
  Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Events

- [ ] List Events [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Event Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Memberships

- [ ] List Memberships [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create Membership [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Membership Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Membership [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Membership [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Messages

- [ ] List Messages [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] List Direct Messages [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Message [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Edit a Message [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Message Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Message [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Messages with Edit

- [ ] List Messages [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] List Direct Messages [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Message [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Edit a Message [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Get Message Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Message [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### People

- [ ] List People [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Person [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Person Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Person [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Person [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()
- [ ] Get My Own Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Room Tabs

- [ ] List Room Tabs [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Room Tab [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Room Tab Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Room Tab [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Room Tab [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Rooms

- [ ] List Rooms [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Room [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Room Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Room Meeting Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Room [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Room [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Team Memberships

- [ ] List Team Memberships [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Team Membership [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Team Membership Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Team Membership [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Team
  Membership [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

#### Teams

- [ ] List Teams [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Team [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Get Team Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Team [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Delete a Team [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()

#### Webhooks

- [ ] List Webhooks [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Create a Webhook [![POST method](https://img.shields.io/static/v1.svg?label=&message=POST&color=orange)]()
- [ ] Get Webhook Details [![GET method](https://img.shields.io/static/v1.svg?label=&message=GET&color=green)]()
- [ ] Update a Webhook [![PUT method](https://img.shields.io/static/v1.svg?label=&message=PUT&color=blue)]()
- [ ] Delete a Webhook [![DELETE method](https://img.shields.io/static/v1.svg?label=&message=DELETE&color=red)]()

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
