# Awesome! How do I contribute?

Contributions are welcome, and are accepted via pull requests. Please review these guidelines before submitting any pull requests.

## Guidelines

* Code style is enforced with [Laravel Pint](https://laravel.com/docs/pint). Run `composer pint` before committing (or `composer pint:check` to verify without changing files). We also follow [PSR-12](https://www.php-fig.org/psr/psr-12/) and [PHP-FIG Naming Conventions](https://www.php-fig.org/bylaws/psr-naming-conventions/). This project supports PHP 8.4 and 8.5 with Laravel 12 and 13; type declarations are required (Larastan runs at level max with no baseline).
* Ensure that the current tests pass (`composer test` – Pest), and if you've added something new, add the tests where relevant.
* Remember that we follow [SemVer](http://semver.org). If you are changing the behaviour, or the public api, you may need to update the docs.
* Send a coherent commit history, making sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash](http://git-scm.com/book/en/Git-Tools-Rewriting-History) them before submitting.
* You may also need to [rebase](http://git-scm.com/book/en/Git-Branching-Rebasing) to avoid merge conflicts.

## Commit messages

We use [Conventional Commits](https://www.conventionalcommits.org/): `type(scope): description`. Use imperative, lowercase description, no period at the end. Types used in this repo: `feat`, `fix`, `docs`, `chore`, `refactor`, `test`, `ci`.

## Branches

- `main` is the default and release branch: every pull request merged into it is released.
- `develop` collects work in progress; merging into `develop` releases nothing.

## Checks

Before opening a pull request run `composer test`, `composer stan` and `composer pint:check`. CI also runs the PHP 8.4/8.5 x Laravel 12/13 matrix, the lowest supported versions, 100 % line coverage, `composer validate --strict`, `composer audit` and actionlint. Tests must never reach the network (`Http::preventStrayRequests()` is on).

## Releases

Releases are automatic; never create tags or releases by hand.

1. When a pull request into `main` is merged, the CI run of its merge commit runs on `main`.
2. When that run succeeds, the `Release` workflow creates the next stable tag on that exact merge commit and a GitHub Release with the pull request's description. Merges are released in the order they landed; a merge whose CI failed, was cancelled or skipped gets no release of its own and is listed in the next release.
3. The bump:
   - one label `release:major`, `release:minor` or `release:patch`, when present;
   - otherwise the Conventional Commit title: `feat:` → minor, anything else (fix, docs, ci, chore, deps...) → patch;
   - `type!:` in the title or a `BREAKING CHANGE:` line in the body always means major.
   The `Release policy` check fails before merge when two release labels are set or a breaking change has a lower label. Describe breaking changes and the migration in the pull request body: it becomes the release notes.

Recovery (a missed event, a failed run, the merge that installed the workflow): run the `Release` workflow manually with the pull request number. It re-validates the pull request through the API, defaults to a dry run, and never creates a second tag or release for the same pull request; an existing tag is never moved.
