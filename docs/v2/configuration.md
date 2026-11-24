# Configuration

## Tokens

The bearer token is resolved **for every request**, in this order:

1. a token bound to the client: `LaravelWebex::forToken($token)->messages()->...` (one client per Webex account);
2. the `webex.token_resolver` class, a `Offlineagency\LaravelWebex\Contracts\TokenResolver` whose `token()` is called before each request (refresh OAuth tokens there; safe in queue workers and Octane);
3. `config('webex.bearer')`.

## Events

When a client is created, `AuthenticationRequested` is dispatched (a listener may set the token), then `WebexClientConfigured`. Neither means that Webex accepted the token: a rejected token comes back as an `Error` with `status` 401. `SuccessfulAuthentication` is still dispatched after `WebexClientConfigured` but is deprecated.

## Retries

`GET`, `PUT` and `DELETE` are retried after a network failure, a `429` or a `503`, waiting for `Retry-After` (seconds or HTTP date, capped by `webex.retry.max_delay_seconds`, default 30) or an exponential delay; `webex.retry.times` is the number of attempts (default 3, `1` disables retries). `POST` and `PATCH` are never retried. When Webex still answers 429, the `Error` carries `status` and `retryAfter`.

## The /auth route

`GET /auth` (route name `auth`) is registered only when `WEBEX_AUTH_ROUTE_ENABLED=true`; it dispatches `WebexClientConfigured`. Change its path and name with `webex.auth_route`.
