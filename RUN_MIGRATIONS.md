# How to Run Database Migrations

Since Laravel can't bootstrap without the required database tables, you have **two options**:

## Option 1: Run SQL Script Directly on Database (RECOMMENDED)

This is the fastest way to get your site working.

### Steps:

1. **Connect to your production database** using:
   - phpMyAdmin
   - MySQL Workbench
   - Command line: `mysql -h YOUR_HOST -u YOUR_USER -p YOUR_DATABASE`
   - Any database management tool

2. **Run the SQL script**:
   - Open the file: `database/migrations-sql.sql`
   - Copy and paste the entire SQL script into your database tool
   - Execute it

   This will create the essential tables:
   - `sessions` - for session storage
   - `cache` - for cache storage
   - `cache_locks` - for cache locking
   - `jobs` - for queue jobs
   - `job_batches` - for job batching
   - `failed_jobs` - for failed jobs
   - `users` - user accounts
   - `migrations` - migration tracking

3. **After running the SQL**, your site should work!

4. **Then run full Laravel migrations** via the web endpoint:
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate"
   ```
   
   Or visit: https://dentalschedule.vercel.app/migrate (POST request)

## Option 2: Use Vercel CLI to Run Migrations Locally

If you have database access from your local machine:

1. **Pull environment variables**:
   ```bash
   vercel env pull .env.production
   ```

2. **Run migrations** (connects to production database):
   ```bash
   php artisan migrate --force
   ```

## Option 3: Temporary File-Based Sessions (Already Configured)

I've updated the code to automatically fallback to file-based sessions and cache if database tables don't exist. This means:

- Your site **should work** even without database tables (using file-based sessions/cache)
- Once you create the database tables, you can switch back to database sessions/cache by setting environment variables:
  - `SESSION_DRIVER=database`
  - `CACHE_STORE=database`

## Quick Start (Recommended)

1. **Run the SQL script** (`database/migrations-sql.sql`) on your production database
2. **Test your site**: https://dentalschedule.vercel.app
3. **Run full migrations** via the endpoint to create all application tables

## Database Connection Info

Your database credentials are stored in Vercel environment variables:
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

To view them:
```bash
vercel env ls
```

## Troubleshooting

### "Table already exists" errors
This is fine! The SQL script uses `CREATE TABLE IF NOT EXISTS`, so it's safe to run multiple times.

### Can't connect to database
- Verify your database allows connections from external IPs
- Check firewall settings
- Ensure SSL/TLS is configured if required
- Verify credentials in Vercel environment variables

### Site still shows 500 error
1. Check that the SQL script ran successfully
2. Verify database connection in Vercel environment variables
3. Check Vercel dashboard for error logs
4. Try the migration endpoint: `POST /migrate`

