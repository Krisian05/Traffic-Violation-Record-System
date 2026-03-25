# Temporary Free Deployment on Railway

This setup is for short-term online testing, not permanent production.

## Important Limitation

This app stores uploaded photos and files on the local Laravel `public` disk.

On Railway, local filesystem storage is ephemeral by default. That means uploaded files may disappear after a redeploy or restart unless you later add a persistent volume or move uploads to object storage.

For login, navigation, reports, and basic demo use, Railway is fine.

## Files Added For Railway

- [railway/init-app.sh](/c:/xampp/htdocs/TVRS%20V2/railway/init-app.sh)
- [.env.railway.example](/c:/xampp/htdocs/TVRS%20V2/.env.railway.example)

## What Railway Needs

Based on Railway's Laravel guide, deploy from a GitHub repo, add your environment variables, then generate a public domain for the app service.

Official guide:

- https://docs.railway.com/guides/laravel

## Fastest Setup

1. Push this project to GitHub.
2. Create a new Railway project.
3. Deploy from the GitHub repo.
4. Add a MySQL service in Railway.
5. In the app service, set the Pre-Deploy Command to:

```sh
chmod +x ./railway/init-app.sh && sh ./railway/init-app.sh
```

6. Add environment variables from [.env.railway.example](/c:/xampp/htdocs/TVRS%20V2/.env.railway.example).
7. Generate a public Railway domain from the service Networking settings.

## Required Variables

At minimum, set:

- `APP_KEY`
- `APP_URL`
- `DB_CONNECTION=mysql`
- `DB_HOST=${{MySQL.MYSQLHOST}}`
- `DB_PORT=${{MySQL.MYSQLPORT}}`
- `DB_DATABASE=${{MySQL.MYSQLDATABASE}}`
- `DB_USERNAME=${{MySQL.MYSQLUSER}}`
- `DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}`
- `DEFAULT_ADMIN_PASSWORD`

## Generate APP_KEY

Run locally:

```powershell
php artisan key:generate --show
```

Paste the output into Railway as `APP_KEY`.

## What The Init Script Does

On each deploy it will:

- clear old caches
- create `public/storage` if missing
- run database migrations
- rebuild Laravel caches

## Admin Login After Deploy

If you seed the database before exporting data or create the admin manually in Railway, use your chosen admin password.

If the deployed database is empty, create the first admin account after deployment with either:

```powershell
php artisan db:seed
```

or a one-time manual insert workflow on the server.

## Safer Testing Advice

Use Railway for:

- login testing
- navigation testing
- showing the app online
- mobile/browser access

Avoid relying on it for:

- permanent uploaded evidence photos
- long-term storage
- final production deployment
