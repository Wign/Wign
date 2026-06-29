# TODO

- [ ] **Lock down prod MySQL** — set prod `.env` `DB_HOST=127.0.0.1`, firewall port 3306 to localhost in Forge, then rotate the `forge` DB password (old one is exposed).
- [ ] **Make local `.env` safe** — set `DB_HOST=db` + `APP_ENV=local` so dev tooling can never reach production again.
