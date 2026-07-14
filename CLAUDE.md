# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Wign is a crowdsourced Danish Sign Language *encyclopedia* (not a dictionary) — a Wikipedia-like platform where deaf users contribute and vote on video signs for words. Built on **Laravel 6 / PHP 8.0**. The primary UI language is Danish.

## Development environment

Local development is Docker-based and driven by `make` (it builds the image with the developer's UID/GID so Apache can serve the bind-mounted files):

```bash
make up        # build image (if needed) + docker-compose up; app served at http://localhost:8080
make build     # build the dev image only (Dockerfile.dev); touches .built to avoid rebuilds
make clean     # rm .built to force a rebuild
```

`docker-compose.yaml` services: `app` (Apache, port 8080), `db` (MySQL 5.7, host port 4306), `testdb` (MySQL 5.7, host port 5306), and `migrater` (waits for db, then runs `php artisan migrate:fresh --force --seed`). DB credentials and hosts come from `.env` (copy `.env.example`).

> ## 🛑 BEFORE running ANY artisan DB command, check where `.env` points
>
> The repo's `.env` has historically held **production** credentials
> (`APP_ENV=production`, `DB_HOST=<the live DigitalOcean droplet IP>`, `DB_DATABASE=forge`).
> The `app` service has **no DB override in `docker-compose.yaml`** — it reads `.env`
> verbatim — and `make up` / the `migrater` service run **`migrate:fresh --force --seed`**,
> which **DROPS EVERY TABLE** and reseeds with ~100 fake signs.
>
> On 2026-06-23 this combination **wiped the live wign.dk database** (recovered from an
> old dump — see [docs/incident-2026-06-23-db-wipe.md](docs/incident-2026-06-23-db-wipe.md)).
>
> **Rules:**
> 1. For local work, `.env` MUST have `DB_HOST=db` and `APP_ENV=local`. Verify with
>    `grep -E 'APP_ENV|DB_HOST' .env` and confirm it is **not** a public IP.
> 2. Never run `migrate`, `migrate:fresh`, or `db:seed` — and never `docker compose up`
>    the `migrater` service — until you've confirmed `.env` points at the local `db` container.
> 3. Keep production credentials **out** of the repo `.env`; they live in Laravel Forge.

> **Dev image runs PHP 8.0, not 7.2.** Laravel 6 cannot boot on PHP 8.1+ (it turns
> their deprecations into fatal errors) and `composer install` fails on PHP 7.2
> (`composer.json` requires `^8.0`). PHP **8.0** is the only version that satisfies
> both, so `Dockerfile.dev` is pinned to `php:8.0-apache`. Confirmed via the Forge
> dashboard (2026-07-14): **production also runs PHP 8.0**, so there is no real
> version mismatch — the repo's root `Dockerfile` (`php:7.2-apache`) does **not**
> reflect production. Forge doesn't use Docker at all (see Deployment below), and
> even locally `make build` only reads that file's timestamp to decide whether to
> rebuild — it never actually builds from it (see the Makefile rebuild caveat
> below). Treat the root `Dockerfile` as stale/vestigial, likely left over from the
> earlier AWS Elastic Beanstalk setup. See
> [docs/local-development.md](docs/local-development.md) for the full setup
> walkthrough and [docs/maintenance-2026-06.md](docs/maintenance-2026-06.md) for
> what was changed and why.

> **Makefile rebuild caveat:** the `.built` marker depends on the *production*
> `Dockerfile`, so editing `Dockerfile.dev` does not trigger a rebuild — run
> `make clean` (or `rm .built`) first.

### Frontend assets (Laravel Mix)

```bash
npm run development   # one-off build
npm run watch         # rebuild on change
npm run production    # minified build
```

Mix compiles **only** `resources/assets/sass/app.scss` → `public/css/style.css` (see `webpack.mix.js`). The JS in `public/js/script.js` is hand-maintained, not bundled.

### Tests

PHPUnit 9, run inside the app container:

```bash
vendor/bin/phpunit                              # all suites (Feature + Unit)
vendor/bin/phpunit --testsuite Unit             # one suite
vendor/bin/phpunit tests/Unit/SignServiceTest.php
vendor/bin/phpunit --filter testGetAllSign      # single test
```

Tests run against the `testing` DB connection (`APP_ENV=testing` in `phpunit.xml`), which points at the `testdb` container via `DB_TEST_*` env vars. Tests use `RefreshDatabase` and the `factory()` helpers in `database/factories`.

## Architecture

**Request flow:** thin controllers (`app/Http/Controllers`) delegate domain logic to services (`app/Services`: `SignService`, `WordService`, `TagService`), which operate on Eloquent models in `app/`. Keep business logic in the services, not the controllers.

**Domain model** (models live directly in `app/`, not `app/Models`):
- `Word` ⟶ hasMany `Sign`, hasMany `RequestWord`. Query scopes like `withSign()`, `latestWords()`, `getQueriedWord()` drive most listing/search logic.
- `Sign` ⟶ belongsTo `Word`, hasMany `Vote`, morphToMany `Tag`. Uses `SoftDeletes`. A `RequestWord` is a user request for a word that has no sign yet.
- **Flagging:** flagging a sign (`SignController::flagSign`) sets `flag_reason`/`flag_comment`/`flag_email`/`flag_ip` and then soft-deletes it. The `noFlagged()` scope (`whereNull('flag_reason')`) hides flagged signs everywhere.
- **Voting** is IP-based (no user accounts for voters); `SignService::assignVotesToSign` attaches vote counts and whether the current IP has voted.

**URL paths are config-driven.** Routes reference `config('wign.urlPath.*')` (defined in `config/wign.php`) rather than hardcoded strings. There is a Danish→English URL migration: old Danish paths (`tegn`, `opret`, `seneste`, …) 301-redirect to the new English ones. When adding or renaming a route, update `config/wign.php` and `routes/web.php` together.

**Public API** lives in `routes/api.php` and serves the `api.{APP_DOMAIN}` subdomain (e.g. `api.wign.dk`) via `ApiController` — endpoints like `hasSign/{word}`, `video/{word}`, `words/{query}`.

**Global middleware:** `App\Http\Middleware\Blacklist` runs on every request (IP/word blacklisting backed by the `blacklist` table). `TrustProxies`/`ValidProxies` matter because the app runs behind an AWS load balancer.

**External integrations:**
- **Video hosting: CameraTag.** New-sign form fields are prefixed `wign01_*` (`wign01_uuid`, `wign01_vga_mp4`, etc.); the CameraTag key is `config('wign.cameratag.id')` from the `CT_KEY` env var.
- **Slack:** a webhook notification fires on each new sign (`SignController::sendSlack`, `config('social.slack.webHook')`).
- Localized strings use `__('flash.…')` keys resolved from `resources/lang/da.json`.

## Deployment

**Production runs on Laravel Forge → a single DigitalOcean droplet** (`weathered-haze`,
nginx + PHP-FPM, app at `/home/forge/wign.dk`), with **MySQL 8 on the same droplet**
(database `forge`). This is the current reality; the repo still contains older
**AWS Elastic Beanstalk** tooling (`Dockerfile.aws`, the `aws` compose service, `make
aws-shell`, `docs/devops.md`) from a previous hosting setup — treat AWS references as
historical unless verified.

**Deploy script (confirmed 2026-07-14, from the Forge dashboard, not checked into
this repo):** `git pull origin $FORGE_SITE_BRANCH` → `composer install
--no-interaction --prefer-dist --optimize-autoloader` (installs exactly what's
pinned in `composer.lock`; **never** `composer update`) → FPM reload → `php artisan
migrate --force` (applies pending migrations forward; **never** `migrate:fresh`). A
normal Forge deploy cannot drop tables — the wipe risk documented above is specific
to the local `migrater` Docker service when `.env` points at prod, not to deploying
this repo. Not verifiable from the repo/dashboard access so far: whether **Quick
Deploy** (auto-deploy on push to `$FORGE_SITE_BRANCH`) is enabled — check before
assuming a push to `master` deploys automatically.

### Database — hosting, backups, recovery

- The live DB is MySQL on the droplet. It is reached via the credentials in production
  `.env` (`DB_HOST` = droplet IP). **MySQL was internet-exposed on port 3306** — it
  should be firewalled to localhost; verify before assuming it's locked down.
- There is **no automated backup**: no binlog, no Forge DB backup job, no DigitalOcean
  automated backups (only occasional manual droplet snapshots).
- The data originally lived at **Gigahost** (`mysql9.gigahost.dk`, db `thanerik_wign`)
  before the move to Forge/DigitalOcean. A migration export sits at
  **`/home/forge/wign.dk/backup/backup.sql`** on the droplet (Dec 2024, ~6,000 signs) —
  this is what the live site was restored from after the 2026-06-23 wipe.
- **If you ever need to restore:** load a dump into the `forge` DB (the dump has no
  `DROP TABLE`, so reset the DB first: `DROP DATABASE forge; CREATE DATABASE forge`),
  then `php artisan config:clear && cache:clear`. **Never** `migrate:fresh` to "fix" prod.
- The 2016→Nov-2024 sign history was recovered; anything added Nov 2024 → Jun 2026 was
  lost in the wipe. Full story: [docs/incident-2026-06-23-db-wipe.md](docs/incident-2026-06-23-db-wipe.md).
