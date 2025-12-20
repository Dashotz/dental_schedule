# Running Migrations with XAMPP

You can use XAMPP's MySQL to run migrations locally. This is perfect for testing!

## Prerequisites

1. **XAMPP is running** ✅ (you mentioned you're already running it)
2. **MySQL service is started** in XAMPP Control Panel
3. **Database exists** - Create it if it doesn't exist yet

## Step 1: Create the Database (if needed)

1. Open **phpMyAdmin** (usually at http://localhost/phpmyadmin)
2. Click **"New"** in the left sidebar
3. Enter database name: `dental_schedule`
4. Choose collation: `utf8mb4_unicode_ci`
5. Click **"Create"**

## Step 2: Verify Your .env File

Your `.env` file should have:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dental_schedule
DB_USERNAME=root
DB_PASSWORD=
```

This matches XAMPP's default MySQL configuration.

## Step 3: Run Migrations

Open a terminal/command prompt in your project directory and run:

```bash
php artisan migrate
```

Or if you want to run without confirmation prompts:

```bash
php artisan migrate --force
```

## Step 4: Verify Tables Were Created

1. Go to phpMyAdmin: http://localhost/phpmyadmin
2. Select the `dental_schedule` database
3. You should see tables like:
   - `users`
   - `sessions`
   - `cache`
   - `cache_locks`
   - `jobs`
   - `migrations`
   - `patients`
   - `appointments`
   - And all your other application tables

## Alternative: Run SQL Script Directly

If you prefer, you can also run the SQL script directly in phpMyAdmin:

1. Open phpMyAdmin
2. Select `dental_schedule` database
3. Click **"SQL"** tab
4. Open the file: `database/migrations-sql.sql`
5. Copy and paste the SQL into the SQL tab
6. Click **"Go"**

This will create the essential tables. Then run `php artisan migrate` to create the rest.

## For Production (Vercel)

**Important:** XAMPP's MySQL is only accessible locally. For Vercel deployment, you need:

1. **A cloud database** (like PlanetScale, AWS RDS, DigitalOcean, etc.)
2. **Or** set up remote access to XAMPP (NOT recommended for production)

The database credentials in Vercel environment variables should point to your production database, not XAMPP.

## Testing Locally

After running migrations with XAMPP, you can:
- Test your application locally
- Verify all tables are created correctly
- Make sure everything works before deploying

Then when you're ready for production, use the same migration process on your production database.

