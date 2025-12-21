# HelioHost Deployment Guide

This guide will help you deploy the Dental Schedule application to HelioHost using Plesk and Git repository installation.

## Prerequisites

- HelioHost account with Plesk access
- Git repository (GitHub, GitLab, or Bitbucket) with your code
- SSH public key generated and added to your Git provider
- MySQL database created in HelioHost control panel

## Step 1: Prepare Your Repository

1. Ensure your code is pushed to your Git repository (GitHub, GitLab, or Bitbucket)
2. Make sure your repository is public or you have SSH access configured

## Step 2: Get Your SSH Public Key from Plesk

1. In the Plesk Laravel installation interface, you'll see an SSH public key displayed
2. Copy this SSH public key (click the copy icon)
3. Add this SSH key to your Git provider:
   - **GitHub**: Settings → SSH and GPG keys → New SSH key
   - **GitLab**: Settings → SSH Keys
   - **Bitbucket**: Personal settings → SSH keys

## Step 3: Install Application via Plesk

1. In Plesk, navigate to **Laravel** → **Install Application**
2. Select your domain: `dental-schedule.helioho.st`
3. Choose **"Install from remote repository"** (cloud icon)
4. Enter your repository URL in the **Repository** field:
   ```
   git@github.com:yourusername/dental_schedule.git
   ```
   Or for HTTPS (if using HTTPS instead of SSH):
   ```
   https://github.com/yourusername/dental_schedule.git
   ```
5. The SSH public key should already be displayed (use the one from Step 2)
6. Click **"Install Application"**

## Step 4: Configure Environment Variables

After installation, you need to configure your `.env` file:

1. In Plesk, navigate to your domain's file manager
2. Locate the `.env` file in the root directory
3. Update the following values:

```env
APP_NAME="Dental Schedule"
APP_ENV=production
APP_KEY=base64:GWmbop02L2mlj2tJdcgi77Ws7beyv7pEPNKz/oVOz1M=
APP_DEBUG=false
APP_URL=http://dental-schedule.helioho.st

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

# MySQL Database Configuration (from HelioHost control panel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_heliohost_db_name
DB_USERNAME=your_heliohost_db_username
DB_PASSWORD=your_heliohost_db_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

# Mail Configuration (update with your HelioHost mail settings)
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@dental-schedule.helioho.st"
MAIL_FROM_NAME="${APP_NAME}"
```

### Important Notes:
- Replace `your_heliohost_db_name`, `your_heliohost_db_username`, and `your_heliohost_db_password` with your actual MySQL credentials from HelioHost
- Update `APP_URL` with your actual domain
- The `APP_KEY` should remain the same (already generated)

## Step 5: Set File Permissions

In Plesk File Manager or via SSH, ensure proper permissions:

```bash
# Navigate to your application root
cd /path/to/your/application

# Set storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (if needed)
chown -R your_user:your_group storage bootstrap/cache
```

Or via Plesk File Manager:
- Right-click `storage/` folder → Permissions → Set to `775`
- Right-click `bootstrap/cache/` folder → Permissions → Set to `775`

## Step 6: Install Dependencies

1. In Plesk, navigate to **Laravel** → Your application
2. Use the **"Run Command"** feature or SSH to run:

```bash
composer install --no-dev --optimize-autoloader
```

## Step 7: Run Database Migrations

Run the following commands via Plesk's command interface or SSH:

```bash
# Run migrations
php artisan migrate --force

# Seed the database with default users
php artisan db:seed --class=UserSeeder
```

## Step 8: Clear and Cache Configuration

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 9: Verify Installation

1. Visit your domain: `http://dental-schedule.helioho.st`
2. You should see the welcome page or login page
3. Test login with the seeded users:
   - **Admin**: `admin@dental` / `password`
   - **Doctor**: `doctor@dental` / `password`
   - **Staff**: `staff@dental` / `password`

## Step 10: Configure Document Root (if needed)

If Plesk didn't automatically set it:
1. In Plesk, go to **Domains** → Your domain → **Hosting Settings**
2. Set **Document root** to: `/path/to/your/app/public`
3. Save changes

## Troubleshooting

### Issue: 500 Internal Server Error
- Check file permissions on `storage/` and `bootstrap/cache/`
- Verify `.env` file exists and is configured correctly
- Check Laravel logs: `storage/logs/laravel.log`

### Issue: Database Connection Error
- Verify MySQL credentials in `.env`
- Ensure MySQL database exists in HelioHost control panel
- Check if `DB_HOST` is set to `localhost`

### Issue: Application Not Found
- Verify document root is set to `public/` directory
- Check that `artisan` file exists in the root directory
- Ensure all files were cloned from Git repository

### Issue: Permission Denied
- Set `storage/` and `bootstrap/cache/` to `775` permissions
- Ensure web server user has write access

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong database passwords
- [ ] `APP_KEY` is set and secure
- [ ] File permissions are correct (storage writable, but not world-writable)
- [ ] `.env` file is not publicly accessible
- [ ] Regular backups configured

## Maintenance Commands

### Update Application
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

## Support

For HelioHost-specific issues, refer to:
- HelioHost Documentation: https://wiki.heliohost.org/
- HelioHost Support Forums: https://www.helionet.org/

For Laravel-specific issues:
- Laravel Documentation: https://laravel.com/docs

---

**Last Updated**: December 2025

