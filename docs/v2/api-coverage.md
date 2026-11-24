# API coverage

The implemented operations and the known gaps are listed in [WEBEX_ENDPOINT_COVERAGE.md](../../WEBEX_ENDPOINT_COVERAGE.md). The package does not cover every Webex API. Every public endpoint method is checked by `tests/Contract` against the official [Webex OpenAPI specification](https://github.com/webex/webex-openapi-specs): each request it sends must exist in the API.
