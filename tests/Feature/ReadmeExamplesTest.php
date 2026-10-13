<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebexFacade as LaravelWebex;

/**
 * The README examples, run through the facade: each one must send the method and path of the official Webex
 * OpenAPI specification (webex/webex-openapi-specs, public-spec/webex-messaging.json and webex-meeting.json).
 */
it('sends the documented method and path for every README example', function () {
    Http::fake(['*' => Http::response(['id' => 'x', 'items' => []], 200)]);

    LaravelWebex::meeting()->list(['state' => 'inProgress', 'max' => 10]);
    LaravelWebex::meeting()->detail('meetingId');
    LaravelWebex::meeting()->create('My Meeting', '2026-11-15T10:00:00Z', '2026-11-15T11:00:00Z', ['agenda' => 'Agenda']);
    LaravelWebex::meeting()->destroy('meetingId');
    LaravelWebex::meeting_invitees()->list('meetingId');
    LaravelWebex::meeting_participants()->list('meetingId');
    LaravelWebex::messages()->list('roomId');
    LaravelWebex::messages()->listWithPagination('roomId', ['max' => 20]);
    LaravelWebex::messages()->create('roomId', 'Hello!');
    LaravelWebex::messages()->detail('messageId');
    LaravelWebex::messages()->edit('messageId', 'Updated text');
    LaravelWebex::messages()->destroy('messageId');
    LaravelWebex::rooms()->list();
    LaravelWebex::rooms()->create('My Room');
    LaravelWebex::rooms()->detail('roomId');
    LaravelWebex::rooms()->update('roomId', ['title' => 'New Title']);
    LaravelWebex::rooms()->meetingDetails('roomId');
    LaravelWebex::rooms()->destroy('roomId');
    LaravelWebex::teams()->list();
    LaravelWebex::teams()->create('My Team');
    LaravelWebex::teams()->update('teamId', 'New Name');
    LaravelWebex::teams()->destroy('teamId');
    LaravelWebex::memberships()->list();
    LaravelWebex::memberships()->create('roomId', 'user@example.com');
    LaravelWebex::memberships()->update('membershipId', ['isModerator' => true]);
    LaravelWebex::memberships()->destroy('membershipId');
    LaravelWebex::people()->listPeople();
    LaravelWebex::people()->detailPerson('personId');
    LaravelWebex::webhooks()->listWebhooks();
    LaravelWebex::webhooks()->createWebhook('My Webhook', 'https://example.com/webhook', 'meetings', 'created');
    LaravelWebex::webhooks()->destroyWebhook('webhookId');

    $sent = Http::recorded()->map(fn (array $pair) => $pair[0])->map(
        fn (Request $request) => $request->method().' '.parse_url($request->url(), PHP_URL_PATH)
    )->all();

    expect($sent)->toBe([
        'GET /v1/meetings', 'GET /v1/meetings/meetingId', 'POST /v1/meetings', 'DELETE /v1/meetings/meetingId',
        'GET /v1/meetingInvitees', 'GET /v1/meetingParticipants',
        'GET /v1/messages', 'GET /v1/messages', 'POST /v1/messages', 'GET /v1/messages/messageId', 'PUT /v1/messages/messageId', 'DELETE /v1/messages/messageId',
        'GET /v1/rooms', 'POST /v1/rooms', 'GET /v1/rooms/roomId', 'PUT /v1/rooms/roomId', 'GET /v1/rooms/roomId/meetingInfo', 'DELETE /v1/rooms/roomId',
        'GET /v1/teams', 'POST /v1/teams', 'PUT /v1/teams/teamId', 'DELETE /v1/teams/teamId',
        'GET /v1/memberships', 'POST /v1/memberships', 'PUT /v1/memberships/membershipId', 'DELETE /v1/memberships/membershipId',
        'GET /v1/people', 'GET /v1/people/personId',
        'GET /v1/webhooks', 'POST /v1/webhooks', 'DELETE /v1/webhooks/webhookId',
    ]);
});

it('returns an Error that the README tells callers to check', function () {
    Http::fake(['*' => Http::response(['message' => 'The requested resource could not be found.', 'trackingId' => 'ROUTER_1'], 404)]);

    $result = LaravelWebex::messages()->detail('missing');

    expect($result)->toBeInstanceOf(Error::class);
    assert($result instanceof Error);
    expect($result->message)->toBe('The requested resource could not be found.')
        ->and($result->trackingId)->toBe('ROUTER_1');
});

it('creates a webhook with the string resource and event the Webex API requires', function () {
    Http::fake(['*' => Http::response(['id' => 'w1', 'resource' => 'meetings', 'event' => 'created'], 200)]);

    LaravelWebex::webhooks()->createWebhook('My Webhook', 'https://example.com/webhook', 'meetings', 'created', ['secret' => 's']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->data() === ['name' => 'My Webhook', 'targetUrl' => 'https://example.com/webhook', 'resource' => 'meetings', 'event' => 'created', 'secret' => 's']);
});
