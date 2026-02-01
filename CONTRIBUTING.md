# Awesome! How do I contribute?

Contributions are welcome, and are accepted via pull requests. Please review these guidelines before submitting any pull requests.

## Guidelines

* Please follow the [PSR-2 Coding Standard](http://www.php-fig.org/psr/psr-2/) and [PHP-FIG Naming Conventions](http://www.php-fig.org/bylaws/psr-naming-conventions/).
* Ensure that the current tests pass (`composer test` – Pest), and if you've added something new, add the tests where relevant.
* Remember that we follow [SemVer](http://semver.org). If you are changing the behaviour, or the public api, you may need to update the docs.
* Send a coherent commit history, making sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash](http://git-scm.com/book/en/Git-Tools-Rewriting-History) them before submitting.
* You may also need to [rebase](http://git-scm.com/book/en/Git-Branching-Rebasing) to avoid merge conflicts.

## Commit messages

We use [Conventional Commits](https://www.conventionalcommits.org/): `type(scope): description`. Use imperative, lowercase description, no period at the end. Types used in this repo: `feat`, `fix`, `docs`, `chore`, `refactor`, `test`, `ci`.
