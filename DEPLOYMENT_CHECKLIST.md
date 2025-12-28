# Production Deployment Checklist

**UiTM Credit Exemption Management System - DigitalOcean Deployment**

This checklist ensures all critical security fixes and production configurations are in place before deploying to DigitalOcean.

---

## CRITICAL SECURITY FIXES (Must Complete Before Deployment)

### 1. Environment Configuration

- [ ] **Set `APP_ENV=production`** in production .env file
- [ ] **Set `APP_DEBUG=false`** in production .env (CRITICAL - prevents stack trace exposure)
- [ ] **Set `LOG_LEVEL=warning`** or `error` in production .env
- [ ] **Generate new `APP_KEY`** with `php artisan key:generate`
- [ ] **Update `APP_URL`** with production IP or domain
- [ ] **Set `SESSION_SECURE_COOKIE=true`** (requires HTTPS)

### 2. Credentials Security

- [ ] **Rotate Gmail app password** (exposed password: `lectwrqiiiusnysb` must be revoked)
  - Go to: https://myaccount.google.com/apppasswords
  - Revoke old password
  - Generate new 16-character app password
  - Update `MAIL_PASSWORD` in production .env
- [ ] **Create strong database password** (minimum 32 characters, not "root")
- [ ] **Never commit .env file** to Git (verify it's in .gitignore)

### 3. Production Assets

- [x] **Built production assets** with `npm run build`
  - Verify `public/build/` directory exists
  - Contains `assets/` folder with compiled CSS/JS

### 4. Development Code Removal

- [x] **Email preview route protected** with environment guard
  - Route only accessible in local/staging environments
- [x] **Test seeders protected** with environment guards
  - AcademicAdvisorTestSeeder.php ✓
  - ExternalLecturerSeeder.php ✓
  - ProgramCoordinatorSeeder.php ✓
  - ResourcePersonSeeder.php ✓

---

## PRE-DEPLOYMENT VERIFICATION

### Code Quality

- [ ] Run `php artisan config:clear` to clear cached configs
- [ ] Run `php artisan route:clear` to clear cached routes
- [ ] Run `composer dump-autoload --optimize` for autoloader optimization
- [ ] Verify no syntax errors: `php -l` on key files

### Security Audit

- [ ] Google Cloud credentials file NOT in Git repository (check .gitignore)
- [ ] No exposed API keys or secrets in codebase
- [ ] File upload directories not publicly accessible
- [ ] Strong passwords for all production user accounts

### Database Preparation

- [ ] Database migrations tested locally
- [ ] Essential seeders identified (CampusSeeder, FacultySeeder, CourseSeeder)
- [ ] Test seeders will NOT run in production (environment guards added)

---

## DIGITALOCEAN SETUP

### GitHub Student Pack

- [ ] Applied for GitHub Student Developer Pack
- [ ] Verified student status approved
- [ ] Claimed DigitalOcean $200 credit (valid 1 year)
- [ ] Created NEW DigitalOcean account (required for Student Pack)

### Droplet Configuration (Recommended)

**Droplet Size**: 2 vCPU, 4 GB RAM, 80 GB SSD ($24/month)
- [ ] Region: Singapore (SGP1) for low latency to Malaysia
- [ ] OS: Ubuntu 22.04 LTS x64
- [ ] Authentication: SSH key (recommended) or strong password
- [ ] Hostname: `uitm-credit-system`

### Firewall Configuration

- [ ] Inbound SSH (Port 22): Your IP only
- [ ] Inbound HTTP (Port 80): All IPv4/IPv6
- [ ] Inbound HTTPS (Port 443): All IPv4/IPv6
- [ ] MySQL (Port 3306): Localhost only
- [ ] Outbound: All protocols (for updates, API calls)

---

## SERVER SETUP

### System Updates

```bash
apt update && apt upgrade -y
```

### LEMP Stack Installation

**Nginx:**
```bash
apt install nginx -y
systemctl start nginx
systemctl enable nginx
```

**MySQL:**
```bash
apt install mysql-server -y
mysql_secure_installation
# Set root password, remove anonymous users, disallow remote root login
```

**PHP 8.2:**
```bash
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update
apt install php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
    php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip \
    php8.2-bcmath php8.2-gd php8.2-intl php8.2-fileinfo -y
php -v  # Verify PHP 8.2.x
```

**Composer:**
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

**Node.js & NPM:**
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install nodejs -y
node -v   # Should show v20.x
```

**Git:**
```bash
apt install git -y
```

---

## DATABASE SETUP

### Create Production Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE uitm_credit_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'uitm_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_32_CHAR_PASSWORD';

GRANT ALL PRIVILEGES ON uitm_credit_system.* TO 'uitm_user'@'localhost';

FLUSH PRIVILEGES;

EXIT;
```

### Test Database Connection

```bash
mysql -u uitm_user -p uitm_credit_system
# Verify access, then EXIT
```

---

## APPLICATION DEPLOYMENT

### Clone Repository

```bash
mkdir -p /var/www
cd /var/www
git clone https://github.com/khayreen/credit-exemption-system.git uitm-credit-system
cd uitm-credit-system
```

### Set Permissions

```bash
chown -R www-data:www-data /var/www/uitm-credit-system
chmod -R 755 /var/www/uitm-credit-system
chmod -R 775 /var/www/uitm-credit-system/storage
chmod -R 775 /var/www/uitm-credit-system/bootstrap/cache
```

### Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### Configure Environment

```bash
cp .env.example .env
nano .env
# Edit with production values (see CRITICAL SECURITY FIXES section)
```

**Critical .env Settings:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_DROPLET_IP
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_DATABASE=uitm_credit_system
DB_USERNAME=uitm_user
DB_PASSWORD=YOUR_STRONG_PASSWORD

SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=NEW_APP_PASSWORD_HERE
MAIL_ENCRYPTION=tls
```

### Generate Application Key

```bash
php artisan key:generate
```

### Upload Google Cloud Credentials

**From local machine:**
```bash
scp C:\path\to\service-account-credentials.json root@YOUR_DROPLET_IP:/var/www/uitm-credit-system/storage/app/keys/
```

**On server:**
```bash
chmod 600 /var/www/uitm-credit-system/storage/app/keys/service-account-credentials.json
chown www-data:www-data /var/www/uitm-credit-system/storage/app/keys/service-account-credentials.json
```

### Run Migrations

```bash
php artisan migrate --force
```

### Seed Essential Data

```bash
php artisan db:seed --class=CampusSeeder
php artisan db:seed --class=FacultySeeder
php artisan db:seed --class=CourseSeeder
php artisan db:seed --class=CS251CourseEquivalencySeeder
```

### Scrape External Data

```bash
php artisan scrape:institutions
php artisan scrape:uitm-degree-programs
php artisan scrape:uitm-diploma-programs
```

### Create Storage Link

```bash
php artisan storage:link
```

### Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer dump-autoload --optimize
```

---

## NGINX CONFIGURATION

### Create Server Block

```bash
nano /etc/nginx/sites-available/uitm-credit-system
```

**Configuration:**
```nginx
server {
    listen 80;
    listen [::]:80;

    server_name YOUR_DROPLET_IP;
    root /var/www/uitm-credit-system/public;

    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Laravel entry point
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Deny access to storage and keys
    location ~ ^/storage/app/keys {
        deny all;
    }

    location ~ ^/storage/app/transcripts {
        deny all;
    }

    location ~ ^/storage/app/syllabi {
        deny all;
    }

    # Client max body size for PDF uploads
    client_max_body_size 10M;
}
```

### Enable Site

```bash
ln -s /etc/nginx/sites-available/uitm-credit-system /etc/nginx/sites-enabled/
nginx -t  # Test configuration
systemctl reload nginx
```

---

## QUEUE WORKERS SETUP

### Install Supervisor

```bash
apt install supervisor -y
```

### Configure Queue Worker

```bash
nano /etc/supervisor/conf.d/uitm-queue-worker.conf
```

**Configuration:**
```ini
[program:uitm-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/uitm-credit-system/artisan queue:work database --tries=3 --timeout=300
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/uitm-credit-system/storage/logs/queue-worker.log
stopwaitsecs=3600
```

### Start Queue Workers

```bash
supervisorctl reread
supervisorctl update
supervisorctl start uitm-queue-worker:*
supervisorctl status  # Verify running
```

---

## CRON JOBS (Laravel Scheduler)

```bash
crontab -e -u www-data
```

**Add:**
```cron
* * * * * cd /var/www/uitm-credit-system && php artisan schedule:run >> /dev/null 2>&1
```

**Verify:**
```bash
php artisan schedule:list
```

---

## TESTING & VERIFICATION

### Access Application

- [ ] Open browser: `http://YOUR_DROPLET_IP`
- [ ] Verify login page loads without errors
- [ ] Check browser console for JavaScript errors

### Create First Admin User

```bash
php artisan tinker

$user = new App\Models\User();
$user->name = 'System Administrator';
$user->email = 'kaiarenne00@gmail.com';
$user->password = bcrypt('SECURE_PASSWORD_HERE');
$user->email_verified_at = now();
$user->save();

$coordinator = new App\Models\Coordinator();
$coordinator->user_id = $user->id;
$coordinator->save();

exit
```

### Test Critical Workflows

- [ ] User registration with 2FA setup
- [ ] Login with Google Authenticator
- [ ] Student application submission
- [ ] Transcript upload and OCR processing
- [ ] Course equivalency matching (CS251 program)
- [ ] Lecturer review and forwarding
- [ ] Email notifications (syllabus requests)

### Monitor Logs

```bash
# Real-time Laravel logs
php artisan pail --timeout=0

# Queue worker logs
tail -f /var/www/uitm-credit-system/storage/logs/queue-worker.log

# Nginx error logs
tail -f /var/log/nginx/error.log
```

---

## POST-DEPLOYMENT SECURITY

### Security Hardening

- [ ] Change SSH port from 22 to custom port
- [ ] Set up fail2ban for brute-force protection
- [ ] Configure UFW firewall
- [ ] Disable root SSH login (use sudo user)
- [ ] Enable automatic security updates

### Monitoring & Backups

- [ ] Set up daily database backups
- [ ] Configure log rotation
- [ ] Set up uptime monitoring (UptimeRobot, etc.)
- [ ] Create backup strategy for transcripts/syllabi

---

## COST TRACKING

**Expected Monthly Costs (with GitHub Student Pack):**
- Droplet (2 vCPU, 4GB RAM): $24/month
- Weekly Snapshots (4 retained): $4.80/month
- **Total**: $28.80/month ($144 for 5 months)
- **Remaining Credit**: $56 buffer from $200 Student Pack

**External Costs:**
- Google Cloud Vision API: ~$1.50 per 1000 documents (use $300 GCP free tier)
- Gmail SMTP: Free

---

## ROLLBACK PLAN

### Database Backup

```bash
mysqldump -u uitm_user -p uitm_credit_system > backup_$(date +%Y%m%d).sql
```

### Droplet Snapshot

- DigitalOcean Control Panel > Droplet > Snapshots
- Create snapshot before major changes
- Restore in 5 minutes if needed

### Code Rollback

```bash
cd /var/www/uitm-credit-system
git log --oneline
git checkout PREVIOUS_COMMIT_HASH
composer install --optimize-autoloader --no-dev
php artisan config:clear && php artisan cache:clear
```

---

## SUPPORT RESOURCES

**DigitalOcean Documentation:**
- Laravel Deployment: https://www.digitalocean.com/community/tutorials/how-to-deploy-laravel-applications-on-ubuntu-22-04
- MySQL Setup: https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-22-04
- Nginx Configuration: https://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-22-04

**Laravel Documentation:**
- Deployment: https://laravel.com/docs/12.x/deployment
- Queues: https://laravel.com/docs/12.x/queues
- Scheduling: https://laravel.com/docs/12.x/scheduling

**GitHub Student Pack:**
- DigitalOcean Benefits: https://www.digitalocean.com/github-students
- GitHub Education: https://education.github.com/pack

---

## DEPLOYMENT STATUS

**Last Updated**: 2025-12-29

**Production Readiness**: ⚠️ Ready after completing CRITICAL SECURITY FIXES

**Estimated Deployment Time**: 10-14 hours over 3-5 days

**Current Status**:
- ✅ Development routes protected with environment guards
- ✅ .env.example updated with production settings
- ✅ Production assets built (public/build/)
- ✅ Test seeders protected with environment guards
- ⚠️ Credentials rotation required (Gmail app password)
- ⚠️ Production .env file needs configuration
- ⚠️ Google Cloud credentials need upload

**Next Steps**:
1. Rotate exposed Gmail app password
2. Claim GitHub Student Pack ($200 credit)
3. Create DigitalOcean droplet
4. Follow deployment steps above
5. Test thoroughly before announcing to users

---

**For detailed deployment guide and recommendations, see:** `~/.claude/plans/foamy-tickling-candy.md`
