# Running Wign locally

A practical guide to running Wign on your own machine for testing. Everything
runs in Docker, so you do **not** need PHP, MySQL, or Composer installed on your
host — only Docker.

> **Why not just `npm run dev`?** That only compiles the CSS/JS assets. Wign is a
> **Laravel 6 PHP application**, so to see it in a browser you need a PHP web
> server *and* a MySQL database running — which is what Docker provides below.

## Prerequisites (one-time)

1. **Docker Desktop** installed and running on Windows.
2. **WSL integration enabled:** Docker Desktop → **Settings** → **Resources** →
   **WSL Integration** → turn it on for your distro → **Apply & Restart**.

Verify it works from inside WSL:

```bash
docker --version
docker compose version
```

Both should print a version. If `docker` is "not found", the WSL integration
isn't enabled yet.

## Start it (from the repo root: `/home/radu/dev/Wign`)

### 1. Build the dev image

```bash
make build
```

This builds the `wign:app` image from `Dockerfile.dev` (PHP 8.0 + Apache).
First time takes a couple of minutes; afterwards it's cached.

> If you ever edit `Dockerfile.dev`, run `make clean` first — otherwise the
> rebuild is skipped (see the note at the bottom).

### 2. Start the containers

```bash
docker compose up -d db testdb app
```

This starts three containers in the background:

| Container | What | Host port |
|-----------|------|-----------|
| `wign-app-1` | Apache + PHP 8.0 (the site) | **8080** |
| `wign-db-1` | MySQL 5.7 (app database) | 4306 |
| `wign-testdb-1` | MySQL 5.7 (test database) | 5306 |

Give MySQL ~10 seconds to initialise on the very first run. Check status with:

```bash
docker compose ps
```

### 3. Create and seed the database

```bash
docker exec -w /var/www wign-app-1 php artisan migrate:fresh --force --seed
```

This builds the schema and loads sample data (about 175 words / 100 signs).
You only need to do this on first run, or whenever you want a clean reset.

### 4. Open it

Go to **http://localhost:8080** — you should see the Wign front page.

## Everyday commands

```bash
docker compose ps                 # what's running
docker compose logs -f app        # watch the app's logs (errors show here)
docker compose stop               # stop containers (keeps data)
docker compose up -d              # start them again
docker compose down               # stop AND remove containers (DB data is lost)
```

Reset the database any time:

```bash
docker exec -w /var/www wign-app-1 php artisan migrate:fresh --force --seed
```

Run any other artisan command the same way, e.g.:

```bash
docker exec -w /var/www wign-app-1 php artisan route:list
```

## Troubleshooting

- **`docker: command not found`** — WSL integration isn't enabled (see Prerequisites).
- **Front page shows a database error** — the DB containers may still be starting,
  or you haven't run the `migrate:fresh --seed` step yet.
- **Port 8080 already in use** — something else is using it; stop that, or change
  the `app` port mapping in `docker-compose.yaml`.
- **You edited `Dockerfile.dev` but nothing changed** — run `make clean` (or
  `rm .built`) then `make build`. The Makefile's rebuild marker watches the
  production `Dockerfile`, not `Dockerfile.dev`.
- **Lots of `PUSHER_APP_KEY`/`version is obsolete` warnings** — harmless, ignore them.

## Background / why the setup looks like this

The repo originally targeted PHP 7.2, which no longer builds, and the codebase
can't run on the very latest PHP either. The dev image is therefore pinned to
**PHP 8.0**. The full reasoning and the list of edited files are in
[maintenance-2026-06.md](maintenance-2026-06.md).
