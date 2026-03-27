# AWS Deployment Plan

This project is a Laravel application intended for serious, long-term use. For a police-facing deployment, the recommended AWS stack is:

- Amazon EC2 for the Laravel app
- Amazon RDS for MySQL for the database
- Amazon S3 for uploaded files and evidence images
- Amazon Route 53 for DNS
- AWS Certificate Manager (ACM) for SSL
- Optional: CloudFront, WAF, CloudWatch, Systems Manager

## Recommended Architecture

Minimum production architecture:

- 1 EC2 instance running Nginx + PHP-FPM
- 1 RDS MySQL instance
- 1 S3 bucket for file uploads
- 1 Route 53 hosted zone
- 1 ACM certificate

Safer police-grade architecture:

- 2 EC2 instances in private subnets behind an Application Load Balancer
- 1 Multi-AZ RDS MySQL instance
- 1 S3 bucket with versioning and lifecycle rules
- Route 53 public hosted zone
- ACM certificate on the load balancer
- CloudWatch logs and alarms
- AWS WAF
- Systems Manager Session Manager instead of open SSH where possible

## Step-by-Step Deployment Plan

### 1. Domain and DNS

- Get an official government-approved domain if this is a real police deployment.
- If that is not available yet, use a temporary custom domain.
- Create a Route 53 hosted zone for the domain.

Reference:

- https://aws.amazon.com/route53/pricing/
- https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/Welcome.html

### 2. Upload Storage Strategy

- Do not keep uploaded evidence and photos on the EC2 filesystem.
- Create an S3 bucket dedicated to application uploads.
- Enable bucket versioning.
- Block public bucket access.
- Serve files through signed URLs or controlled access rules if needed.

Reference:

- https://aws.amazon.com/s3/pricing

### 3. Database

- Create an RDS MySQL instance.
- Start with Single-AZ for budget-sensitive testing.
- Use Multi-AZ for real production availability.
- Enable automated backups.
- Restrict access so only the app server security group can connect.

Reference:

- https://aws.amazon.com/rds/mysql/pricing/
- https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_PIOPS.Autoscaling.html

### 4. Application Server

- Launch an Ubuntu EC2 instance.
- Install:
  - Nginx
  - PHP 8.2+
  - Composer
  - MySQL client
- Clone the GitHub repository.
- Create the production `.env`.
- Run:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. HTTPS

- Request an ACM certificate for the domain.
- If using a load balancer, attach the certificate there.
- Redirect all traffic to HTTPS.

### 6. Security

- Use IAM users with MFA.
- Limit EC2 inbound rules to only necessary ports.
- Keep the database private.
- Turn on CloudWatch monitoring and alerts.
- Keep OS packages and PHP updated.

### 7. Backups and Recovery

- Use RDS automated backups and snapshots.
- Enable S3 versioning for file recovery.
- Keep a tested restore process.

## Estimated Monthly Cost

These are rough planning ranges, not final quotes. Exact AWS cost depends on region, storage, traffic, and availability design.

### Lean Production

- EC2 small instance: about `$15 to $30/month`
- RDS MySQL single-AZ small instance: about `$20 to $60/month`
- S3 uploads: about `$1 to $10/month` at low to moderate usage
- Route 53 hosted zone: about `$0.50/month` plus query charges
- Domain: varies by TLD, usually `$12 to $30/year`

Estimated total:

- about `$40 to $100/month`

### Safer Real Production

- 2 EC2 instances or 1 larger EC2 behind a load balancer: about `$30 to $100+/month`
- RDS MySQL Multi-AZ: about `$80 to $250+/month`
- S3 uploads and backups: about `$5 to $30+/month`
- Route 53: about `$1 to $5+/month`
- Load balancer: about `$18+/month` before traffic
- CloudFront/WAF/monitoring: extra depending on traffic

Estimated total:

- about `$130 to $400+/month`

Primary AWS pricing references:

- EC2 pricing: https://aws.amazon.com/ec2/pricing/on-demand/
- RDS MySQL pricing: https://aws.amazon.com/rds/mysql/pricing/
- S3 pricing: https://aws.amazon.com/s3/pricing
- Route 53 pricing: https://aws.amazon.com/route53/pricing/

## Changes This Laravel Project Needs Before AWS

This project can run on AWS, but it should be adjusted before a serious deployment.

### 1. Move uploaded files from local disk to S3

Current issue:

- The app stores many uploaded files to the `public` local disk.
- Several Blade views render uploaded files using `asset('storage/...')`, which assumes local filesystem storage.

Impact:

- If you move uploads to S3, those hardcoded local-storage URLs will break.

Needed change:

- Switch file storage to S3.
- Replace local-path assumptions with `Storage::url(...)` or temporary/signed URL strategies consistently.

### 2. Stop hardcoding the `public` disk in controllers

Current issue:

- Multiple controllers call `store(..., 'public')` and `Storage::disk('public')`.

Needed change:

- Use a configurable disk such as the default filesystem disk or an explicit app upload disk that can point to S3.

### 3. Decide on session strategy

Current issue:

- Current production example uses `SESSION_DRIVER=file`.

Impact:

- File sessions are not a good scaling strategy if you later use multiple EC2 instances.

Needed change:

- For a single EC2 instance, file sessions are acceptable initially.
- For a scalable deployment, move sessions to `database` or `redis`.

### 4. Review cache and queue strategy

Current issue:

- Current production example uses file cache and sync queue.

Needed change:

- For initial deployment, this is acceptable.
- For more robust production, use Redis or database-backed queue/cache as needed.

### 5. Add S3 environment values

You already have S3 config placeholders in Laravel, which is good, but they need real values:

- `AWS_ACCESS_KEY_ID`
- `AWS_SECRET_ACCESS_KEY`
- `AWS_DEFAULT_REGION`
- `AWS_BUCKET`
- optional `AWS_URL`

### 6. Create a real AWS production `.env`

You should use a dedicated AWS production `.env` with:

- real domain
- RDS host and credentials
- S3 bucket settings
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain`

### 7. Add operational hardening

Before real police use:

- centralize logs
- define admin password rotation
- remove test/demo defaults
- verify database backup and restore
- review authorization and auditing policies

## Recommended Rollout Path

### Phase 1

- Single EC2
- Single-AZ RDS
- S3 uploads
- Route 53
- HTTPS

Use this for internal pilot/testing.

### Phase 2

- Move to Multi-AZ RDS
- Add a load balancer
- Add second app server
- Add monitoring and WAF

Use this for real public or operational rollout.
