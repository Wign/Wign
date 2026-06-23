# Maintenance log — June 2026 (getting Wign to run locally again)

This note records what was changed to make the old repo runnable on a current
machine, and *why*. Wign is a Laravel 6 app from ~2019; some of its tooling had
bit-rotted. Nothing about the application's behaviour was changed — only the
local **development** setup.

## TL;DR

The dev Docker image was rebuilt on **PHP 8.0** (it was pinned to PHP 7.2 on an
end-of-life Debian release that no longer installs). The app now runs at
http://localhost:8080. See [local-development.md](local-development.md) to start it.

## Files changed

| File | Change | Why |
|------|--------|-----|
| `Dockerfile.dev` | Rewrote it: base image `php:7.2-apache` → `php:8.0-apache`; removed the `apt-get` step; added an `error_reporting` tweak; dropped the in-image `composer install`. | See the three problems below. |
| `CLAUDE.md` | Added notes about the PHP 8.0 requirement and the Makefile rebuild caveat. | So this isn't rediscovered from scratch. |
| `docs/local-development.md` | New file. | Step-by-step "how to run it locally". |
| `docs/maintenance-2026-06.md` | New file (this one). | Record of what/why. |

> The production `Dockerfile` was **not** touched — it still targets PHP 7.2 +
> modsecurity exactly as before.

## The three problems that blocked startup

### 1. Docker wasn't reachable from WSL
`docker` was not on the PATH inside the WSL distro. Fixed by enabling **Docker
Desktop → Settings → Resources → WSL Integration** for the distro.

### 2. The PHP 7.2 base image no longer builds
`Dockerfile.dev` started `FROM php:7.2-apache`, which is built on Debian
*Buster*. Buster is end-of-life and its package repositories were moved to an
archive server, so the image's `apt-get update` now returns **404** and the
build dies.

### 3. PHP version mismatch (7.2 vs 8.0 vs 8.4)
Three different PHP versions were in play and none agreed:

- `composer.json` requires **PHP `^8.0`** — so `composer install` *fails* on the
  image's PHP 7.2.
- The host machine has **PHP 8.4**. Laravel 6's bootstrap forces
  `error_reporting(-1)` and converts every PHP notice — including the flood of
  "implicitly nullable parameter" **deprecations** that PHP 8.4 emits — into a
  fatal `ErrorException`. So Laravel 6 cannot boot on 8.4 (or 8.1+).
- That leaves exactly one version that satisfies everything: **PHP 8.0** — new
  enough for `composer.json`'s `^8.0`, old enough that the deprecations that
  crash Laravel 6 don't exist yet. 8.0 is also the newest PHP that Laravel 6.20
  officially supports.

### The fix
`Dockerfile.dev` is now:

```dockerfile
FROM php:8.0-apache
RUN docker-php-ext-install -j"$(nproc)" pdo_mysql opcache \
    && a2enmod rewrite \
    && echo 'error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT' \
       > /usr/local/etc/php/conf.d/zz-wign-dev.ini
WORKDIR /var/www
EXPOSE 80
ADD apache/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD apache/prod.htaccess /var/www/public/.htaccess
ARG userid=1000
ARG groupid=1000
RUN usermod -u ${userid} www-data || true
```

Key points:
- **No `apt-get`** — `pdo_mysql` and `opcache` compile without extra OS packages,
  which sidesteps the dead Buster repositories entirely.
- **No in-image `composer install`** — at runtime `docker-compose` bind-mounts the
  project (including the host's `vendor/`, which was installed under PHP 8.x and
  satisfies `^8.0`) over `/var/www`, so the container reuses it directly.
- The `error_reporting` ini line is a harmless belt-and-suspenders; Laravel
  overrides it at boot anyway, which is why **8.0 (not a php.ini tweak) is the
  actual fix**.

## Verification performed
- Image built cleanly on `php:8.0-apache`.
- `php artisan migrate:fresh --force --seed` succeeded (11 migrations + seeders;
  **175 words / 100 signs**).
- HTTP 200 from `/`, `/signs`, `/all`, `/ask`, `/about`, and a real `/sign/{word}`
  page.

## Known rough edges (not blocking)
- The `migrater` compose service may not auto-run on `make up`; run the
  `migrate:fresh` command manually (see local-development.md).
- `docker compose` prints `PUSHER_APP_KEY`/`version is obsolete` warnings — cosmetic.
- The `Makefile` `.built` marker tracks the production `Dockerfile`, so changes to
  `Dockerfile.dev` need `make clean` (or `rm .built`) to force a rebuild.
