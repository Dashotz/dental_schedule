# PlanetScale Setup Guide for Vercel

This guide will walk you through setting up PlanetScale as your production database.

## Step 1: Create PlanetScale Account

1. Go to https://planetscale.com
2. Click **"Sign up"** (you can use GitHub, Google, or email)
3. Complete the signup process

## Step 2: Create a Database

1. Once logged in, click **"Create database"** or **"New database"**
2. Fill in the details:
   - **Database name**: `dental-schedule` (or any name you prefer)
   - **Region**: Choose closest to you (e.g., `us-east-1`)
   - **Plan**: Select **"Hobby"** (free tier)
3. Click **"Create database"**

## Step 3: Get Connection Details

1. Click on your newly created database
2. Click the **"Connect"** button (top right)
3. You'll see connection options. Select **"General"** or **"PHP"**
4. You'll see connection details like:
   ```
   Host: xxxxx.aws.connect.psdb.cloud
   Username: xxxxx
   Password: pscale_xxxxx
   Database: dental-schedule
   Port: 3306
   ```

5. **Important**: Click **"Create password"** to generate a password (save it immediately, you won't see it again!)

## Step 4: Set Vercel Environment Variables

Now we'll set these values in Vercel. Run these commands one by one:

```bash
vercel env add DB_HOST production
# Paste the Host value (e.g., xxxxx.aws.connect.psdb.cloud)

vercel env add DB_PORT production
# Enter: 3306

vercel env add DB_DATABASE production
# Enter your database name (e.g., dental-schedule)

vercel env add DB_USERNAME production
# Paste the Username value

vercel env add DB_PASSWORD production
# Paste the Password you just created

vercel env add DB_CONNECTION production
# Enter: mysql
```

**Or set them all at once via Vercel Dashboard:**
1. Go to: https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables
2. Click **"Add New"** for each variable
3. Set them for **Production** environment
4. Add all the variables from Step 3

## Step 5: Run Migrations on PlanetScale

You have two options:

### Option A: Run SQL Script in PlanetScale (Recommended)

1. In PlanetScale dashboard, click on your database
2. Click **"Console"** tab (or "SQL Editor")
3. Open the file: `database/migrations-sql.sql` from your project
4. Copy the entire SQL script
5. Paste it into the PlanetScale SQL console
6. Click **"Run"** or press `Ctrl+Enter`

This will create the essential tables.

### Option B: Use Migration Endpoint

After running the SQL script, you can run full Laravel migrations:

1. Make sure your site is deployed with the new environment variables
2. Run:
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate"
   ```

## Step 6: Verify Connection

Test that everything works:

1. Visit your site: https://dentalschedule.vercel.app
2. If you see the welcome page (not a 500 error), the connection works!
3. Check PlanetScale dashboard → **"Insights"** to see connection activity

## Step 7: Run Full Migrations

After the basic tables are created, run all Laravel migrations:

1. **Via Migration Endpoint**:
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate"
   ```

2. **Or via PlanetScale Console**:
   - You can run individual migration SQL if needed
   - Or use the migration endpoint after basic tables exist

## Important PlanetScale Notes

### Connection String Format
PlanetScale uses a connection string like:
```
mysql://username:password@host:3306/database?sslaccept=strict
```

But for Laravel, you set the components separately:
- `DB_HOST` = the host part (without `mysql://`)
- `DB_USERNAME` = username
- `DB_PASSWORD` = password
- `DB_DATABASE` = database name
- `DB_PORT` = 3306

### SSL Required
PlanetScale requires SSL. Laravel handles this automatically when connecting to PlanetScale.

### Branching (Optional)
PlanetScale has a "branching" feature (like Git for databases). For now, use the `main` branch. You can create development branches later if needed.

### Foreign Keys
PlanetScale doesn't support foreign keys by default in the free tier. Your migrations should work, but foreign key constraints won't be enforced. This is usually fine for most applications.

## Troubleshooting

### "Access denied" error
- Double-check your password (create a new one if needed)
- Verify username is correct
- Make sure you're using the correct database name

### "Unknown database" error
- Verify the database name in Vercel matches PlanetScale
- Check that the database exists in PlanetScale dashboard

### Connection timeout
- Verify `DB_HOST` is correct (should end with `.psdb.cloud`)
- Check that `DB_PORT` is `3306`
- Ensure SSL is enabled (Laravel does this automatically)

### Still getting 500 errors
1. Check Vercel logs: `vercel logs https://dentalschedule.vercel.app`
2. Verify all environment variables are set correctly
3. Make sure you ran the SQL script in PlanetScale
4. Try the migration endpoint: `POST /migrate`

## Next Steps After Setup

1. ✅ Database is connected
2. ✅ Basic tables created (sessions, cache, etc.)
3. ✅ Run full migrations
4. ✅ Test your application
5. ✅ Create your first user/admin account

## Quick Reference

**PlanetScale Dashboard**: https://app.planetscale.com
**Your Vercel Project**: https://vercel.com/francis-cruzs-projects/dental_schedule
**Your Site**: https://dentalschedule.vercel.app

Need help? Let me know which step you're on!

