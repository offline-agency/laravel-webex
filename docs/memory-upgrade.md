# Memoria: upgrade a PHP 8.6

Data: 2026-11-24. TARGET_PHP: 8.6 (binario Herd `php86`, PHP 8.6.0; Composer 2.11.2 lanciato con `php86 <Herd>/bin/composer`).

## Gate: VIA LIBERA

| Pacchetto | Verdetto | Note |
|---|---|---|
| php | BUMP | `~8.4.0 \|\| ~8.5.0 \|\| ~8.6.0` |
| laravel/framework | OK | 13.43.0 risolto; Laravel 12 mantenuto nei vincoli |
| larastan, pint, mockery, testbench | OK | |
| pestphp/pest, pest-plugin-laravel | BUMP | `^4.7 \|\| ^5.3` e `^4.1 \|\| ^5.0` |
| ext-json e ext dei pacchetti lockati | OK | tutte incluse in PHP |

Nessun bloccante, nessun pacchetto abbandonato, repository non fork.

## Cosa ha limitato il target framework

Laravel 12 non è installabile su PHP 8.6: Pest 4.7 richiede un paratest senza supporto a 8.6 (le release compatibili richiedono PHPUnit 13, in conflitto con Pest 4.7) e `pest-plugin-laravel` 5 richiede Laravel ^13.23. Il pacchetto dichiara ancora Laravel 12 (valido su 8.4/8.5); la cella CI PHP 8.6 / Laravel 12 è esclusa.

## Passi e problemi

1. Pest 5 consentito (lock aggiornato e testato su PHP 8.5).
2. Vincolo `php` + lock rigenerato su PHP 8.6 + commento `phpstan.neon`.
3. CI, README, UPGRADE, CONTRIBUTING, CHANGELOG.

Test: 1072 passati su PHP 8.6 con `E_ALL`, nessuna deprecazione. Pint e PHPStan (livello max) puliti.

Problemi incontrati:
- Il controllo anti-attribuzione dava un falso positivo su «regenerated with» (contiene `generated with`): messaggio riformulato e branch riscritto con `--force-with-lease`.
- PHPStan stampa un avviso di avvio: `phpstan_turbo` non ha ancora un binario per PHP 8.6 (estensione opzionale).
- `actionlint` non è installato in locale: la modifica a `main.yml` non è stata validata localmente, solo dalla CI.

## Branch e PR

Branch `chore/php-8.6-upgrade`, PR https://github.com/offline-agency/laravel-webex/pull/17

Da fare: verificare la CI su GitHub (cella PHP 8.6 / Laravel 13 con `setup-php`); riabilitare Laravel 12 su PHP 8.6 quando esisterà un Pest 4 compatibile.
