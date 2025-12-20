# Troubleshooting 500 Error

The 500 error means Laravel can't connect to the database or the tables don't exist yet.

## Step 1: Verify SQL Script Was Run in Supabase

**This is the most common cause!**

1. Go to Supabase: https://app.supabase.com
2. Select your project: **dental-schedule**
3. Click **"Table Editor"** in the left sidebar
4. **Do you see these tables?**
   - `sessions`
   - `cache`
   - `cache_locks`
   - `jobs`
   - `users`
   - `migrations`

**If you DON'T see these tables:**
- You need to run the SQL script first!
- Go to **"SQL Editor"** → **"New query"**
- Copy and paste the SQL from `database/migrations-sql-postgres.sql`
- Click **"Run"**

## Step 2: Verify Environment Variables

1. Go to: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables
2. Verify these are set correctly:
   - `DB_CONNECTION` = `pgsql` (not `mysql`)
   - `DB_HOST` = `db.gozrmzlgwxkuqxpzevnc.supabase.co`
   - `DB_PORT` = `5432`
   - `DB_DATABASE` = `postgres`
   - `DB_USERNAME` = `postgres`
   - `DB_PASSWORD` = `Dentalclinic1!`

## Step 3: Test Database Connection

In Supabase, you can test if the connection works:

1. Go to Supabase → **"SQL Editor"**
2. Run this simple query:
   ```sql
   SELECT version();
   ```
3. If this works, your database is accessible

## Step 4: Check Supabase Connection Settings

1. In Supabase, go to **Settings** → **Database**
2. Make sure your project is **active** (not paused)
3. Check if there are any connection restrictions

## Most Likely Issue

**The SQL script hasn't been run yet!**

The tables need to exist before Laravel can connect. Make sure you:
1. ✅ Opened Supabase SQL Editor
2. ✅ Copied the SQL from `database/migrations-sql-postgres.sql`
3. ✅ Pasted and ran it in Supabase
4. ✅ Verified tables were created in "Table Editor"

## Quick Fix

1. **Run the SQL script in Supabase** (if you haven't)
2. **Wait 30 seconds** for changes to propagate
3. **Try the migration endpoint again**

Let me know:
- Have you run the SQL script in Supabase?
- Do you see the tables in Supabase Table Editor?

