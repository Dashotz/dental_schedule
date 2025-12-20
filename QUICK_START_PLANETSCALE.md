# Quick Start: PlanetScale Setup

Follow these steps to get your database running in 10 minutes!

## 🚀 Step-by-Step

### 1. Sign Up & Create Database (2 minutes)

1. Go to: https://planetscale.com
2. Sign up (free account)
3. Click **"Create database"**
4. Name: `dental-schedule`
5. Region: Choose closest to you
6. Plan: **Hobby** (free)
7. Click **"Create database"**

### 2. Get Connection Info (1 minute)

1. Click **"Connect"** button
2. Select **"General"** tab
3. You'll see:
   - Host (e.g., `xxxxx.aws.connect.psdb.cloud`)
   - Username
   - Database name
4. Click **"Create password"** → **Copy the password** (save it!)

### 3. Set Vercel Environment Variables (3 minutes)

**Option A: Via CLI** (recommended)
```bash
vercel env add DB_HOST production
# Paste: xxxxx.aws.connect.psdb.cloud

vercel env add DB_PORT production
# Enter: 3306

vercel env add DB_DATABASE production
# Enter: dental-schedule

vercel env add DB_USERNAME production
# Paste: your-username

vercel env add DB_PASSWORD production
# Paste: your-password

vercel env add DB_CONNECTION production
# Enter: mysql
```

**Option B: Via Dashboard**
1. Go to: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables
2. Add each variable for **Production** environment

### 4. Run SQL Script (2 minutes)

1. In PlanetScale dashboard, click **"Console"** tab
2. Open `database/migrations-sql.sql` from your project
3. Copy all SQL code
4. Paste into PlanetScale console
5. Click **"Run"**

### 5. Redeploy & Test (2 minutes)

```bash
vercel --prod
```

Then visit: https://dentalschedule.vercel.app

### 6. Run Full Migrations

```bash
curl -X POST "https://dentalschedule.vercel.app/migrate"
```

Or visit the migration endpoint in your browser (POST request).

## ✅ Done!

Your database is now connected and ready to use!

## Need Help?

- **PlanetScale Docs**: https://planetscale.com/docs
- **Your Setup Guide**: See `PLANETSCALE_SETUP.md`
- **Troubleshooting**: Check `TROUBLESHOOTING.md`

## Quick Commands Reference

```bash
# View current environment variables
vercel env ls

# Add environment variable
vercel env add VARIABLE_NAME production

# Redeploy
vercel --prod

# Check logs
vercel logs https://dentalschedule.vercel.app
```

