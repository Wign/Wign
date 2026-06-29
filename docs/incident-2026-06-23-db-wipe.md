# Incident — production database wiped & recovered (2026-06-23 → 2026-06-29)

## Summary

On **2026-06-23 ~15:48 CEST**, the live wign.dk database was **dropped and reseeded**
during local development work, replacing ~6,000 real signs with 100 fake seeded ones.
It was discovered on **2026-06-29** and **restored** the same day from a December 2024
migration dump found on the server. The site is back to **6,017 signs / 5,114 words**.

**Permanent loss:** signs/edits added between **Nov 2024 and Jun 2026** (the dump's
newest sign is 2024-11-14). The user reports post-2024 changes were mostly design, so
the real content loss is believed to be small.

## Root cause

A chain of three things, none safe on its own:

1. The repo's **`.env` held production credentials** (`APP_ENV=production`,
   `DB_HOST=206.189.110.99` — the live droplet, `DB_DATABASE=forge`).
2. The Docker **`app` service has no DB override** in `docker-compose.yaml`; it reads
   that `.env` verbatim. Production MySQL was **reachable on port 3306 from the internet**,
   so a laptop could connect straight to it.
3. While bringing the app up locally, `php artisan migrate:fresh --force --seed` was run.
   `migrate:fresh` **drops every table**; the seeders then created 100 fake signs.

The command connected to production (not a local container) and wiped it. The "100 signs"
on the live site afterward were exactly the seeder's output (`factory(Sign, 100)`),
which is what eventually gave the cause away.

## Why it wasn't caught immediately

- It looked local — the assumption was that Docker used the local `db` container.
- Stale docs: CLAUDE.md described production as **AWS Elastic Beanstalk**, while it had
  actually moved to **Laravel Forge / DigitalOcean**, so the live DB's exposure wasn't
  on anyone's radar.
- 6 days passed; the live site kept creating `words` rows (search/requests), inflating
  the word count to ~2,676 and masking that the `signs` table was all fakes.

## Diagnosis (how we confirmed it)

- All `signs` had `created_at = 2026-06-23 15:47`, matching the dev session exactly; the
  descriptions were faker gibberish.
- The table `create_time`s all matched the same minute.
- Row counts matched the seeders precisely (100 signs, 250 votes, 10 blacklist).

## Recovery

The only viable source turned out to be a forgotten file on the droplet:

- **`/home/forge/wign.dk/backup/backup.sql`** — a 9 MB Sequel Ace export from the old
  host (`mysql9.gigahost.dk`, MySQL 5.7, db `thanerik_wign`), dated **2024-12-04**,
  containing **6,022 signs (6,007 live)**, 10,482 words, 1,329 votes, 64,715 request_words.

Restore performed on the droplet:

```bash
cd /home/forge/wign.dk && sudo -u forge php artisan down
mysqldump --single-transaction forge > /root/forge_FAKE_pre-restore.sql   # rollback safety
mysql -e "DROP DATABASE forge; CREATE DATABASE forge CHARACTER SET utf8mb4;"  # dump has no DROP TABLE
mysql forge < /home/forge/wign.dk/backup/backup.sql
sudo -u forge php artisan config:clear && sudo -u forge php artisan cache:clear
sudo -u forge php artisan up
```

Dead ends checked first: no binlog, no Forge backups, no DigitalOcean automated backups,
no `.sql` in git; the only manual droplet snapshot (Sept 2024) predated the data being on
this server and was empty.

## What dead ends taught us about backups

There is **no working backup strategy**. The recovery relied on luck (a 19-month-old
migration export). See follow-ups.

## Follow-ups / hardening

- [ ] **Lock MySQL to localhost** (Forge firewall — close port 3306 to the world).
- [ ] **Rotate the DB password** (`forge` user) — the old one was exposed.
- [ ] Keep production credentials out of the repo `.env`; ship a local-only `.env`
      (`DB_HOST=db`, `APP_ENV=local`) and consider neutralizing the `migrater` service.
- [ ] Set up **real backups**: enable binlog + a daily `mysqldump` (or DigitalOcean
      automated backups), stored off the droplet.
- [ ] Treat `migrate:fresh` as forbidden against any non-local `.env`.
