# Deployment Guide

This project is a Laravel 12 application that runs on PHP 8.2 and MySQL.

## What This App Needs

- PHP 8.2 or newer
- MySQL or MariaDB
- Apache or Nginx
- Composer
- Write access to `storage/` and `bootstrap/cache/`
- Public file access for uploaded photos through `public/storage`

## Important Notes For This Project

- The app currently uses MySQL in local development.
- Uploaded files are stored on Laravel's `public` disk, so `php artisan storage:link` is required.
- The current local `.env` is not production-ready:
  - `APP_ENV=local`
  - `APP_DEBUG=true`
  - `APP_URL=http://tmru.local`
- `QUEUE_CONNECTION=sync` is currently fine for deployment. No queue worker is required unless you change that later.
- No active `@vite` usage was found in the Blade views, so a frontend build is not currently required for the app to render.

## Recommended Production `.env`

Use [`.env.production.example`](/c:/xampp/htdocs/TVRS%20V2/.env.production.example) as your starting point.

At minimum, update these values:

- `APP_URL`
- `APP_KEY`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `MAIL_*` values if you want real email sending

## First-Time Server Setup

From the project root:

```powershell
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If your host does not already contain vendor packages, run:

```powershell
composer install --no-dev --optimize-autoloader
```

## Apache Deployment

Best option:

- Point your domain's document root to the `public/` folder.

Fallback option:

- If your hosting panel only points to the project root, this repository already includes a root [`.htaccess`](/c:/xampp/htdocs/TVRS%20V2/.htaccess) that rewrites requests into `public/`.

Even with that fallback, using `public/` as the document root is still the safer setup.

## Writable Directories

Make sure the web server can write to:

- `storage/`
- `bootstrap/cache/`

If uploads fail on the live server, this is the first thing to check.

## Database

Create a production database, then place the production credentials in `.env`.

After that:

```powershell
php artisan migrate --force
```

## Updating The Live Server

For future deployments:

```powershell
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Run `php artisan storage:link` only once unless the symlink is missing.

## Quick Go-Live Checklist

- `.env` is set to production values
- `APP_DEBUG=false`
- `APP_URL` matches the real domain
- database exists and migrations have run
- `public/storage` link exists
- `storage/` and `bootstrap/cache/` are writable
- document root points to `public/`

## Current Readiness Check

What was verified locally:

- `php artisan config:cache` works
- `php artisan route:cache` works
- `php artisan view:cache` works

What still needs attention:

- `php artisan test` currently has 3 failing feature tests related to delete-route CSRF expectations. This does not automatically block deployment, but it should be cleaned up soon.
