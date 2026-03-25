# Traffic Violation Record System

Laravel-based traffic monitoring and records management system.

## Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Blade

## Local Setup

```powershell
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Default Admin

- Username: `admin`
- Password: `admin123`

## Deployment Notes

- Production guide: [DEPLOYMENT.md](DEPLOYMENT.md)
- Temporary Railway guide: [RAILWAY_DEPLOY.md](RAILWAY_DEPLOY.md)
