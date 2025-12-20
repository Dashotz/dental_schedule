# Quick Start: Supabase Setup (10 Minutes)

Follow these steps to get your database running!

## 🚀 Step-by-Step

### 1. Create Supabase Project (2 minutes)

1. Go to: https://supabase.com
2. Click **"Start your project"**
3. Sign up (GitHub recommended)
4. Click **"New Project"**
5. Fill in:
   - **Name**: `dental-schedule`
   - **Database Password**: Create strong password (save it!)
   - **Region**: Closest to you
   - **Plan**: **Free**
6. Click **"Create new project"**
7. Wait 2-3 minutes

### 2. Get Connection Details (1 minute)

1. Click **"Settings"** (gear icon) → **"Database"**
2. Scroll to **"Connection string"**
3. You'll see:
   - **Host**: `db.xxxxx.supabase.co`
   - **Port**: `5432`
   - **Database**: `postgres`
   - **User**: `postgres`
   - **Password**: (the one you created)

### 3. Set Vercel Environment Variables (3 minutes)

**Via CLI:**
```bash
vercel env add DB_CONNECTION production
# Enter: pgsql

vercel env add DB_HOST production
# Paste: db.xxxxx.supabase.co

vercel env add DB_PORT production
# Enter: 5432

vercel env add DB_DATABASE production
# Enter: postgres

vercel env add DB_USERNAME production
# Enter: postgres

vercel env add DB_PASSWORD production
# Paste: your-password
```

**Or via Dashboard:**
1. Go to: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables
2. Add each variable for **Production**

### 4. Run SQL Script (2 minutes)

1. In Supabase, click **"SQL Editor"** (left sidebar)
2. Click **"New query"**
3. Open `database/migrations-sql-postgres.sql`
4. Copy all SQL code
5. Paste into Supabase SQL editor
6. Click **"Run"** (or Ctrl+Enter)

### 5. Redeploy & Test (2 minutes)

```bash
vercel --prod
```

Then visit: https://dentalschedule.vercel.app

### 6. Run Full Migrations

```bash
curl -X POST "https://dentalschedule.vercel.app/migrate"
```

Or use the migration endpoint in your browser.

## ✅ Done!

Your database is now connected and ready!

## Quick Reference

**Supabase Dashboard**: https://app.supabase.com
**Your Vercel Project**: https://vercel.com/francis-cruzs-projects/dental_schedule
**Your Site**: https://dentalschedule.vercel.app

## Troubleshooting

- **500 Error?** Check that SQL script ran successfully
- **Connection failed?** Verify environment variables are correct
- **Need help?** See `SUPABASE_SETUP.md` for detailed guide

