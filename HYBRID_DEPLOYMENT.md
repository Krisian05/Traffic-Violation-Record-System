# Hybrid Deployment Plan

This deployment uses:

- Laravel Cloud for app hosting
- DigitalOcean Managed MySQL for the database
- Cloudflare R2 for uploaded files

This is a practical middle ground between cheap hobby hosting and a full AWS setup.

## Why This Fits This App

- The app is already Laravel-based, so Laravel Cloud is a natural host.
- The app uses MySQL, so DigitalOcean Managed MySQL fits without a database engine change.
- The app now supports S3-compatible object storage, so Cloudflare R2 works for uploads.

## Expected Monthly Cost

Rough starting estimate:

- Laravel Cloud Starter: pay-as-you-go, custom domains included, usage-based compute and bandwidth
- DigitalOcean Managed MySQL: starts around `$15 to $30+/month` depending on size/high availability
- Cloudflare R2: `10 GB` free, then `$0.015/GB-month` storage on Standard, with free egress

Practical starter range:

- about `$20 to $60/month`

Safer real deployment:

- about `$50 to $120+/month`

Pricing references:

- Laravel Cloud pricing: https://cloud.laravel.com/docs/pricing
- DigitalOcean managed databases: https://www.digitalocean.com/pricing/managed-databases
- Cloudflare R2 pricing: https://developers.cloudflare.com/r2/pricing/

## Step-by-Step Setup

### 1. DigitalOcean Managed MySQL

- Create a Managed MySQL database cluster.
- Create a database for this app.
- Create a database user with a strong password.
- Copy:
  - host
  - port
  - database name
  - username
  - password

### 2. Cloudflare R2

- Create an R2 bucket for uploads.
- Keep bucket public access restricted unless you intentionally want public file delivery.
- Create an API token / access key pair for R2.
- Copy:
  - access key ID
  - secret access key
  - bucket name
  - account ID

R2 is S3-compatible:

- https://developers.cloudflare.com/r2/api/s3/api/

### 3. Laravel Cloud

- Connect your GitHub repo.
- Create the application/environment.
- Add your custom domain.
- Add the environment variables from [`.env.hybrid.example`](/c:/xampp/htdocs/TVRS%20V2/.env.hybrid.example).

### 4. Environment Variables

Use:

- `DB_CONNECTION=mysql`
- `UPLOADS_DISK=s3`
- MySQL values from DigitalOcean
- S3-compatible values from R2

### 5. First Deploy Commands

Laravel Cloud should run the standard deploy flow. Ensure your deployment executes:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Rehearsal Test

Before calling it production-ready:

- log in as admin
- create a motorist with photo
- create a violation with citation and receipt photo
- create an incident with media
- verify uploaded images load correctly
- test edit and delete on uploaded files

## Notes For R2

R2 uses the S3 driver in Laravel. You will need:

- `FILESYSTEM_DISK=local`
- `UPLOADS_DISK=s3`
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `AWS_BUCKET`
- `AWS_DEFAULT_REGION=auto`
- `AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com`

## Best Domain

For real police/government use:

- use an official custom domain, ideally an official government-controlled domain

For temporary serious deployment:

- use your own custom domain on Laravel Cloud, not a platform subdomain
