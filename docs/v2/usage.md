# Usage

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

## Results and errors

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
