# Supabase Setup Guide for Vercel

Complete guide to set up Supabase (PostgreSQL) as your production database.

## Step 1: Create Supabase Account & Project

1. Go to https://supabase.com
2. Click **"Start your project"** or **"Sign up"**
3. Sign up with:
   - GitHub (recommended)
   - Google
   - Email
4. Once logged in, click **"New Project"**
5. Fill in the project details:
   - **Name**: `dental-schedule` (or any name)
   - **Database Password**: Create a strong password (save it immediately!)
   - **Region**: Choose closest to you (e.g., `US East (North Virginia)`)
   - **Pricing Plan**: Select **"Free"**
6. Click **"Create new project"**
7. Wait 2-3 minutes for the project to be created

## Step 2: Get Connection Details

1. In your Supabase project dashboard, click **"Settings"** (gear icon in left sidebar)
2. Click **"Database"** in the settings menu
3. Scroll down to **"Connection string"** section
4. You'll see different connection strings. Use the **"URI"** format or get individual values:
   - **Host**: Found in "Connection string" (e.g., `db.xxxxx.supabase.co`)
   - **Port**: `5432`
   - **Database**: `postgres`
   - **User**: `postgres`
   - **Password**: The one you created in Step 1
   - **Connection string** looks like: `postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres`

## Step 3: Update Laravel Configuration

Laravel already supports PostgreSQL! We just need to update environment variables.

## Step 4: Set Vercel Environment Variables

Run these commands one by one:

```bash
vercel env add DB_CONNECTION production
# Enter: pgsql

vercel env add DB_HOST production
# Paste the host (e.g., db.xxxxx.supabase.co)

vercel env add DB_PORT production
# Enter: 5432

vercel env add DB_DATABASE production
# Enter: postgres

vercel env add DB_USERNAME production
# Enter: postgres

vercel env add DB_PASSWORD production
# Paste your Supabase database password
```

**Or set them via Vercel Dashboard:**
1. Go to: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables
2. Click **"Add New"** for each variable
3. Set them for **Production** environment
4. Add:
   - `DB_CONNECTION` = `pgsql`
   - `DB_HOST` = your Supabase host
   - `DB_PORT` = `5432`
   - `DB_DATABASE` = `postgres`
   - `DB_USERNAME` = `postgres`
   - `DB_PASSWORD` = your Supabase password

## Step 5: Create Database Tables

You have two options:

### Option A: Run SQL Script in Supabase (Recommended)

1. In Supabase dashboard, click **"SQL Editor"** in the left sidebar
2. Click **"New query"**
3. Open the file: `database/migrations-sql-postgres.sql` (I'll create this for you)
4. Copy the entire SQL script
5. Paste it into the Supabase SQL editor
6. Click **"Run"** or press `Ctrl+Enter`

### Option B: Use Migration Endpoint

After running the SQL script, you can run full Laravel migrations:

1. Make sure your site is deployed with the new environment variables
2. Run:
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate"
   ```

## Step 6: Redeploy Your Site

```bash
vercel --prod
```

## Step 7: Test Connection

1. Visit your site: https://dentalschedule.vercel.app
2. If you see the welcome page (not a 500 error), the connection works!
3. Check Supabase dashboard → **"Database"** → **"Tables"** to see your tables

## Step 8: Run Full Migrations

After the basic tables are created, run all Laravel migrations:

```bash
curl -X POST "https://dentalschedule.vercel.app/migrate"
```

Or visit the migration endpoint in your browser (POST request).

## Important Supabase Notes

### Connection Pooling
Supabase uses connection pooling. The connection string format is:
```
postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres
```

But for Laravel, we set the components separately, which works fine.

### SSL Required
Supabase requires SSL connections. Laravel handles this automatically when connecting to Supabase.

### Free Tier Limits
- **Database size**: 500MB
- **Bandwidth**: 2GB/month
- **API requests**: Unlimited
- **Auth users**: 50,000

This is plenty for a dental schedule app!

### Database Extensions
Supabase comes with many PostgreSQL extensions pre-installed. Your Laravel app will work without any additional setup.

## Troubleshooting

### "Connection refused" error
- Verify `DB_HOST` is correct (should end with `.supabase.co`)
- Check that `DB_PORT` is `5432`
- Ensure `DB_PASSWORD` is correct

### "Password authentication failed"
- Double-check your password in Supabase Settings → Database
- You can reset the password if needed

### "Database does not exist"
- Verify `DB_DATABASE` is set to `postgres` (default Supabase database name)

### Still getting 500 errors
1. Check Vercel logs: `vercel logs https://dentalschedule.vercel.app`
2. Verify all environment variables are set correctly
3. Make sure you ran the SQL script in Supabase
4. Try the migration endpoint: `POST /migrate`

## Next Steps After Setup

1. ✅ Database is connected
2. ✅ Basic tables created (sessions, cache, etc.)
3. ✅ Run full migrations
4. ✅ Test your application
5. ✅ Create your first user/admin account

## Quick Reference

**Supabase Dashboard**: https://app.supabase.com
**Your Vercel Project**: https://vercel.com/francis-cruzs-projects/dental_schedule
**Your Site**: https://dentalschedule.vercel.app

## PostgreSQL vs MySQL Differences

Laravel handles most differences automatically, but here are a few things to know:

- **Data types**: PostgreSQL uses slightly different types (Laravel handles this)
- **Quotes**: PostgreSQL uses double quotes for identifiers
- **Auto-increment**: PostgreSQL uses `SERIAL` or `BIGSERIAL` (Laravel handles this)
- **Features**: PostgreSQL has more advanced features than MySQL

Your Laravel migrations will work the same way!

Need help? Let me know which step you're on!

