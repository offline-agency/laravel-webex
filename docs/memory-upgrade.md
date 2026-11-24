# Memoria: upgrade a PHP 8.6

Data: 2026-11-24. TARGET_PHP: 8.6. Modalità: CURRENT (Laravel 12/13 già supportati, CI su PHP 8.4/8.5). SUPPORTED_PHP: 8.4, 8.5, 8.6.
Binari Herd: `php84` (8.4.28), `php85` (8.5.13), `php86` (8.6.0); Composer 2.11.2 lanciato con `phpNN <Herd>/bin/composer`.

## Gate: VIA LIBERA

Nessun bloccante, nessun pacchetto abbandonato, repository non fork. L'albero si risolve su 8.4, 8.5 e 8.6.

| Pacchetto | Verdetto | Vincolo |
|---|---|---|
| php | BUMP | `~8.4.0 \|\| ~8.5.0 \|\| ~8.6.0` |
| laravel/framework | OK | `^12.69.2 \|\| ^13.32` (13.43.0 nel lock) |
| larastan, pint, mockery, testbench | OK | invariati |
| pestphp/pest, pest-plugin-laravel | BUMP | `^4.7 \|\| ^5.3`, `^4.1 \|\| ^5.0` |
| ext-* | OK | tutte incluse in PHP |

## Cosa ha limitato il framework target

Laravel 12 non è installabile su PHP 8.6: Pest 4.7 richiede un paratest senza supporto a 8.6 (le release compatibili richiedono PHPUnit 13, in conflitto con Pest 4.7) e `pest-plugin-laravel` 5 richiede Laravel ^13.23. Laravel 12 resta supportato su 8.4/8.5; la cella CI PHP 8.6 / Laravel 12 è esclusa. Riabilitarla quando esisterà un Pest 4 compatibile.

## Versione

Ultima versione stabile: 2.0.0 (GitHub Release del 2026-10-13, tag senza prefisso `v`). Prossima: **2.1.0** (MINOR: si aggiunge PHP 8.6, non si toglie nulla). Il repository rilascia da solo: al merge in `main` il workflow Release crea tag e GitHub Release con il corpo della PR come note; la PR ha l'etichetta `release:minor`, senza la quale il titolo darebbe una patch (2.0.1). Il CHANGELOG aveva ancora la sezione 2.0 sotto "Unreleased": ora è `[2.0.0] - 2026-10-13` (data della Release) e c'è `[2.1.0] - Unreleased`.

## Documentazione

Layout prima: nessuno (solo README e file in root). Dopo: `docs/README.md` (indice versioni) e `docs/v2/` (index, installation, configuration, usage, pagination, api-coverage, development) ricavati dal README, che ora ha badge, tabella di compatibilità e link. Nessun `upgrading.md` (non è una major; resta `UPGRADE.md` per 1.x → 2.0). Non esiste `.oa-docs.json`. `DOCS_UPDATE.md` e `WEBEX_ENDPOINT_COVERAGE.md` restano in root. Link verificati con uno script (26 link, 0 rotti).

## QA

Pint e Larastan (livello max, senza baseline) c'erano già, con gli script `pint`, `pint:check`, `stan`, `test`; in CI: validate, Pint, Larastan, audit, actionlint, copertura 100 %. Non ho aggiunto nulla.

## Badge

Verificati tutti con `curl` (200, nessuna dicitura di errore): Latest Release, Packagist Version, Downloads, PHP Version, Tests (`main.yml`), Release workflow, codecov, License. Release badge attivo (2.0.0 esiste). Il badge PHP Version legge l'ultima versione pubblicata: mostra `~8.4.0 || ~8.5.0` fino alla release 2.1.0 su Packagist.

## Passi e problemi

1. `chore(deps): allow Pest 5 and its Laravel plugin`
2. `chore(php): support PHP 8.6`
3. `ci: test PHP 8.6 and document the supported platforms`
4. `chore(release): prepare 2.1.0` (ripristinate le note 2.0, nuova sezione 2.1.0)
5. `docs: split documentation by major version`
6. `docs: add badges, compatibility table and links to the versioned docs`

Test: 1072 passati su 8.4, 8.5 e 8.6 con `E_ALL`, nessuna deprecazione; Laravel 12 su 8.4; `--prefer-lowest` su Laravel 12 (8.4) e Laravel 13 (8.4 e 8.6). Il lock risolto con PHP 8.4 è identico. La CI su GitHub era verde su tutte le celle.

Problemi:
- Il controllo anti-attribuzione dava un falso positivo su «regenerated with»: messaggio riformulato, branch riscritto con `--force-with-lease`.
- Le note di 2.0 erano state modificate per errore con PHP 8.6: ripristinate.
- PHPStan stampa un avviso: `phpstan_turbo` non ha un binario per PHP 8.6 (opzionale).
- `actionlint` non è installato in locale.

## Branch e PR

Branch `chore/php-8.6-upgrade`, PR https://github.com/offline-agency/laravel-webex/pull/17, titolo `Support PHP 8.6 (2.1.0)`, etichetta `release:minor`.

Dopo il merge: il workflow Release crea il tag `2.1.0` e la Release; comando manuale di recupero in fondo alla PR. Poi sostituire "Unreleased" con la data nel CHANGELOG e controllare il badge PHP Version.
