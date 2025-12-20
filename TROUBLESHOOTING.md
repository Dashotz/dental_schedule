# Troubleshooting 500 Error on Vercel

## Common Causes

### 1. Missing Database Tables (Most Likely)

If you're using `SESSION_DRIVER=database` and `CACHE_STORE=database`, you need to create the required tables:

**Required Tables:**
- `sessions` - for session storage
- `cache` - for cache storage
- `cache_locks` - for cache locking (optional but recommended)

**Solution:**
Run these migrations on your database:

```bash
# Create sessions table
php artisan session:table

# Create cache table  
php artisan cache:table

# Run all migrations
php artisan migrate
```

Then commit and push the migration files, or run them directly on your production database.

### 2. Database Connection Issues

Verify your database credentials in Vercel:
- Go to: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables
- Check: `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

**Test Connection:**
```bash
# Pull environment variables locally
vercel env pull .env.production

# Test database connection
php artisan tinker
# Then run: DB::connection()->getPdo();
```

### 3. Missing APP_KEY

Ensure `APP_KEY` is set in Vercel environment variables.

**Generate a new key:**
```bash
php artisan key:generate --show
```

Then set it in Vercel:
```bash
vercel env add APP_KEY production
# Paste the generated key when prompted
```

### 4. Check Vercel Logs

View real-time logs:
```bash
vercel logs https://dentalschedule.vercel.app
```

Or check in the dashboard:
https://vercel.com/francis-cruzs-projects/dental_schedule

## Quick Fix Steps

1. **Verify Environment Variables:**
   ```bash
   vercel env ls
   ```

2. **Ensure Database Tables Exist:**
   - Connect to your production database
   - Run: `php artisan migrate --force` (if you have CLI access)
   - Or manually create `sessions` and `cache` tables

3. **Redeploy:**
   ```bash
   vercel --prod
   ```

4. **Check Logs:**
   ```bash
   vercel logs https://dentalschedule.vercel.app
   ```

## Database Tables Schema

If you need to create tables manually:

**Sessions Table:**
```sql
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Cache Table:**
```sql
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Still Having Issues?

1. Enable debug mode temporarily:
   ```bash
   vercel env add APP_DEBUG production
   # Enter: true
   ```
   **⚠️ Remember to set it back to `false` after debugging!**

2. Check the full error in Vercel dashboard logs
3. Verify all environment variables are set correctly
4. Ensure your database allows connections from Vercel's IP ranges

