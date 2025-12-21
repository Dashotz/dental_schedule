# Quick Start - HelioHost Deployment

## Repository URL Format

When installing via Plesk "Install from remote repository", use one of these formats:

### SSH (Recommended)
```
git@github.com:yourusername/dental_schedule.git
```

### HTTPS (Alternative)
```
https://github.com/yourusername/dental_schedule.git
```

Replace `yourusername` with your actual GitHub username and `dental_schedule` with your repository name.

## Quick Checklist

1. ✅ **Add SSH Key to GitHub**
   - Copy SSH public key from Plesk
   - Add to GitHub: Settings → SSH and GPG keys → New SSH key

2. ✅ **Install via Plesk**
   - Select domain: `dental-schedule.helioho.st`
   - Choose "Install from remote repository"
   - Paste repository URL
   - Click "Install Application"

3. ✅ **Configure .env**
   - Update database credentials
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Set `APP_URL` to your domain

4. ✅ **Set Permissions**
   - `storage/` → 775
   - `bootstrap/cache/` → 775

5. ✅ **Run Commands**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan db:seed --class=UserSeeder
   php artisan config:cache
   ```

6. ✅ **Test**
   - Visit your domain
   - Login: `admin@dental` / `password`

For detailed instructions, see [HELIOHOST_DEPLOYMENT.md](HELIOHOST_DEPLOYMENT.md)

