# AWS Rehearsal Checklist

Use this when you are ready to do the first real AWS deployment rehearsal.

## AWS Resources To Create

### Core

- 1 EC2 Ubuntu instance for the app
- 1 RDS MySQL instance
- 1 S3 bucket for uploads
- 1 Route 53 hosted zone
- 1 ACM certificate

### Recommended Names

- EC2 instance: `tvrs-app-prod-01`
- RDS instance: `tvrs-mysql-prod-01`
- S3 bucket: `tvrs-uploads-prod-<unique-suffix>`
- Security group app: `tvrs-app-sg`
- Security group db: `tvrs-db-sg`

## Minimum AWS Settings

### EC2

- OS: Ubuntu 24.04 LTS or current Ubuntu LTS
- Size for first rehearsal: `t3.small` or `t4g.small`
- Storage: at least `30 GB`
- Open ports:
  - `80`
  - `443`
  - `22` only if needed and preferably restricted to your IP

### RDS

- Engine: `MySQL`
- Start with Single-AZ for rehearsal
- Enable automated backups
- Public access: `No`
- Allow inbound only from the app security group

### S3

- Block all public access: `On`
- Bucket versioning: `On`
- Default encryption: `On`

## Production `.env` Values You Will Need

From [`.env.aws.example`](/c:/xampp/htdocs/TVRS%20V2/.env.aws.example):

- `APP_NAME`
- `APP_ENV=production`
- `APP_KEY`
- `APP_DEBUG=false`
- `APP_URL`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `UPLOADS_DISK=s3`
- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `AWS_DEFAULT_REGION`
- `AWS_BUCKET`

## Server Install Commands

On EC2:

```bash
sudo apt update
sudo apt install -y nginx php php-fpm php-cli php-mysql php-xml php-curl php-mbstring php-zip unzip git composer mysql-client
```

## App Deployment Commands

From the project directory:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## S3 Rehearsal Test

Before go-live, test this sequence:

1. Create or edit a motorist with a photo
2. Create a violation with citation photo and vehicle photos
3. Create an incident with media uploads
4. Open the related show page and print page
5. Confirm every image loads from the configured uploads disk

## Rehearsal Pass Criteria

The rehearsal passes only if:

- homepage loads over HTTPS
- login works
- admin account exists
- database writes succeed
- uploads save successfully
- uploaded media displays correctly
- edit and delete operations still work
- reports open without broken images

## After Rehearsal

If the rehearsal passes:

- take an AMI or backup snapshot
- snapshot the RDS instance
- keep a copy of the production `.env`
- document the final DNS cutover steps
