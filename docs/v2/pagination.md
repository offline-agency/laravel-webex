# Pagination

`cursor()` iterates every page lazily (a `LazyCollection`), so `take(50)` only reads the pages it needs:

```php
foreach (LaravelWebex::messages()->cursor($roomId, ['max' => 100]) as $message) {
    // ...
}

$rooms = LaravelWebex::rooms()->cursor()->take(50)->all();
```

The next page is the `Link: rel="next"` URL, followed only on the scheme, host and port of `webex.base_url`, so your token is never sent to another host. A failed page throws `WebexRequestException` (its `error` property is the `Error`); a repeated link or more than `$maxPages` pages (default 100) throws `WebexPaginationException`. `listWithPagination()` still returns one page with its `nextLink`.
