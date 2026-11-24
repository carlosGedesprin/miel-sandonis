# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Website for **Miel Sandonis** (artisan honey producer). It is a custom PHP framework (no Symfony/Laravel kernel) copied from an earlier site (Altira Automations, an AI-automation company, itself built on the "AccedeMe" codebase). The active parts are the public pages (home, nosotros, conservación, nuestra miel, contacto) and the legal notice, privacy and cookie pages. The site is read-only: it has no forms and sends no mail. The old site's blog, its admin/CRM back office (accounts, payments, n8n, RAG, servers…), login, the contact form and the whole mail/cron stack (mail queue, unsubscribe, Turnstile), with their templates and assets, were removed. Only the code the public site needs is left. The site only uses the DB tables `config`, `lang`, `lang_name`, `lang_text`, `lang_text_name`, `site_map` and `bot` (plus the empty `blog_*` tables, kept for a possible future blog). The old site's other tables (users, accounts, invoices, leads, mail queue…) are dropped by `databases/migrations/2026-11-22_eliminar_tablas_web_anterior.sql`, and the blog is emptied by `2026-11-22_vaciar_blog_web_anterior.sql`. Code comments and much of the UI text are in Spanish.

There is no test suite, linter or build step. Assets are plain files under `web/bundles/framework/`.

## Commands

```bash
composer install                       # deps go to vendor/, binaries to bin/ (both gitignored)
php bin/doctrine orm:schema-tool:update --dump-sql   # Doctrine CLI (uses cli-config.php + config/config.env)
php -l path/to/file.php                # only "lint" available
```

You can't run the app standalone. It is served by Apache from `/var/www/html/miel-sandonis` with `mod_rewrite`, and it needs a MySQL database plus `config/config.env`. DB dumps to seed a database are in `databases/<date>/` (numbered SQL files, applied in order, then every file in `databases/migrations/` in date order; dump 1 still creates some old tables that the migrations drop). Data fixes go in `databases/migrations/` as SQL files that someone applies by hand; the dev DB runs in Docker (host `mielsandonis-db`) and isn't reachable from this shell. To check templates without a server, compile and render them with `vendor/` Twig from a scratch PHP script.

## Request flow

1. Root `.htaccess` rewrites everything to `web/`. `web/.htaccess` sends non-file requests to `web/app.php`. `/assets/*` maps to `/web/bundles/framework/*`.
2. `web/app.php` is the front controller and the composition root. It:
   - loads `config/config.env` with phpdotenv (values end up in `$_ENV`; `env_env` = dev/int/prod, `env_log` turns on SQL logging)
   - builds the shared services: `src\util\startup`, `db`, `utils`, `session`, `lang`, Monolog loggers (`var/logs/app.log`, `app_err.log`) and Twig (templates in `app/Resources/views`, with globals `env`, `lang`, `config`, `session`, `uri`, …)
   - defines the routes **inline** with FastRoute. Handlers are strings of the form `'<path under src/controller>:<method>'`, e.g. `'web/webController:indexAction'`. The file is `require_once`d and the class `src\controller\<path with \>` is instantiated.
3. Every controller is built with a single associative array (`env, logger, logger_err, startup, db, utils, session, lang, twig, uri`). `baseViewController`/`baseController` copy the keys that match their properties. To add a route, add the `addRoute` call in `web/app.php` and create the controller at the matching path. Most public routes have both an English and a Spanish URL.

## Layers

- **`src/util/db.php`** is a PDO wrapper and the real data-access layer: `fetchOne/fetchAll($table, $fields, $whereArray, $extraSql)`, `insertArray`, `updateArray`, `delete`, `querySQL`.
- **`src/controller/entity/<x>Controller.php`** are active-record-style table models (extend `baseController`). Only lang_text and lang_text_name remain. Each has `$table`, a `$reg` array of default columns, `getRegbyId()`, `getAll($filter, $extra)`, `persist()`, `delete()`, and getters/setters. Custom queries live in a trait at `entity/repository/<x>RepositoryController.php`.
- **`src/Entity/*.php`** are Doctrine annotation entities. **They are not used at runtime.** They exist only for the Doctrine CLI and schema tooling. The runtime goes through `db.php` and the entity controllers. Only entities for the kept tables remain (blog, bot, lang, lang_text, site_map). `config` has no entity. Don't run `schema-tool:update --force` without reading the SQL first: it would propose dropping `config`.
- **`src/controller/web/`** holds the public site controllers. There is no login: no users, accounts or groups. `src\util\session` only loads the DB config, detects bots (`bot` table) and picks the language. `src/util/utils.php` holds request and URL helpers.
- **Templates** live in `app/Resources/views/web/<skin>/…`. The skin comes from the DB config value `website_skin` (currently `default`). Controllers render `'web/'.$this->session->config['website_skin'].'/page.html.twig'`, and pages extend `web/<skin>.html.twig`. Custom Twig functions are in `src/util/twig/twigFunctions.php`.
- **Page SEO** is driven by variables each page sets at the top: `this_route`, `this_page_title`, `this_page_metadescription` and optionally `og_image` (path under `/assets/images/web/<skin>/`). The layout `web/default.html.twig` turns them into the canonical link, Open Graph/Twitter tags and `<title>`, and holds the JSON-LD. Public page copy (titles, texts, contact details) is hardcoded Spanish in the templates; only legal texts and some labels come from `lang`.
- **Hero**: every page starts with `bands/_hero.html.twig` (full-width photo with white text on a dark shade that fades into it; sizes `home`, `page`, `compact`, text `place` `bottom` or `top`; styles in `css/web/default/hero.css`, loaded globally). Its photos are in `images/web/default/bands/hero/<name>-1920.jpg` and `-960.jpg`. The originals are in the owner's "imagenes miel san donis" folder. When adding a photo, resize it and strip its EXIF (some originals carry GPS coordinates of the apiaries).
- **Accessibility is a requirement (WCAG 2.2 AA)**. Text contrast must be at least 4.5:1 (3:1 for large text and UI borders); check new colors against `main.css`. Text over photos is white on the hero's dark shade (`.hero__band::before`, 62% opacity, which gives 5.2:1 over any photo; see the notes in `hero.css` before changing it). Use rem sizes, never below 0.875rem. Use one `<h1>` per page (the hero title) and no skipped heading levels. Keep focus visible, and leave the skip link, `scroll-padding-top` (fixed header) and the `prefers-reduced-motion` rules in `main.css` in place.
- **Social links**: the owner asked for no Facebook links anywhere (no buttons, no footer link, not in JSON-LD). The layout builds `social_links` (only for JSON-LD `sameAs`) from the other `config.company_*` values, ignoring generic placeholder URLs.
- **Metadata**: `geo.region` is hardcoded to `ES-VA` (Valladolid) in `web/default.html.twig`; the DB value `web_meta_geo_region` was `ES-MAD` and is ignored. The brand is spelled "Sandonis", without an accent.
- **Cookies/analytics**: there is no analytics and no cookie banner. The site only sets the technical `PHPSESSID` cookie, which needs no consent. If analytics is added, the cookie policy must be updated and a consent banner added.
- **Legal pages** (`legalstuff`, `privacy_policy`, `cookiespolicy`) are hardcoded templates. The owner's data lives in one place: the `titular` variable at the top of `web/default.html.twig`, rendered by `legal/_titular.html.twig`. The `WEB_*_TXT` lang texts in the DB are no longer used.
- **Contact page** (`webController::contactAction`, `bands/contactus.html.twig`) only shows the email, phones and opening hours; visitors write by `mailto:` or call. There is no form and no jQuery. If a form is added back, the privacy, cookie and legal-notice texts mention only email and phone, so they would have to be updated too.

## Configuration and i18n

- Runtime settings come from two places: `config/config.env` (DB, mail, company data, domain/protocol) and the DB `config` table, loaded into `$session->config` (e.g. `website_skin`, `web_name`, `cookies_prefix`, `web_enabled`). `config/` is gitignored.
- UI strings live in the DB, not in files. The `lang_text` table holds keys and `lang_text_name` holds the translation per language. `src\util\lang::getLangTexts()` loads them into the Twig global `lang` (e.g. `lang.WEB_MENU_HOME_LINK`). Language comes from the browser or `?lang=xx` and is stored in the session.
- The only session value is the language, `$_SESSION[config['cookies_prefix'].'_locale']`, which the layout reads for `<html lang>`.

## Conventions and gotchas

- Debug tracing is done by `fopen`/`fwrite` to ad-hoc files in `var/logs/` (e.g. `web_app.txt`, `baseViewController___construct.txt`). There are many commented-out `//$txt = …; fwrite($this->myfile, …)` lines. Follow the same style when debugging, and don't remove these lines unless asked. `var/logs/` must be writable, or requests `die()`.
- `web/forbiden_uris.php` blocks known scanner and attack URIs before routing.
