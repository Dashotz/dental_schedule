# Database Migration Setup for Vercel

This guide explains how to run database migrations for your Laravel application on Vercel.

## Automatic Migration During Build

The `vercel.json` is configured to attempt running migrations during the build process. However, this may not always work if:
- The database is not accessible during build time
- Environment variables are not yet available
- Network restrictions prevent database access

If automatic migrations fail, the build will continue (it won't fail the deployment).

## Manual Migration via API Endpoint

We've created migration endpoints that you can call manually after deployment.

### Option 1: Using the Migration Endpoint (Recommended)

1. **Set a secret token** (optional but recommended for security):
   ```bash
   vercel env add MIGRATION_SECRET_TOKEN production
   # Enter a secure random string when prompted
   ```

2. **Run migrations via HTTP request**:
   
   **With token (secure):**
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate?token=YOUR_SECRET_TOKEN"
   ```
   
   **Without token** (if you didn't set MIGRATION_SECRET_TOKEN):
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate"
   ```

3. **Check migration status**:
   ```bash
   curl "https://dentalschedule.vercel.app/migrate/status"
   ```

### Option 2: Using Vercel CLI

1. **Pull environment variables locally**:
   ```bash
   vercel env pull .env.production
   ```

2. **Run migrations locally** (connects to production database):
   ```bash
   php artisan migrate --force
   ```

   **Note:** This requires your local machine to have network access to your production database.

### Option 3: Direct Database Access

If you have direct access to your production database:

1. Connect to your database using your preferred tool (phpMyAdmin, MySQL Workbench, etc.)
2. Run the SQL from the migration files, or
3. Use a database migration tool that supports your database

## Migration Endpoints

### POST `/migrate`
Runs all pending migrations.

**Query Parameters:**
- `token` (optional): Secret token if `MIGRATION_SECRET_TOKEN` is set in environment variables

**Response:**
```json
{
  "success": true,
  "message": "Migrations completed successfully",
  "output": "Migration output..."
}
```

### GET `/migrate/status`
Checks database connection and migration status.

**Response:**
```json
{
  "success": true,
  "status": "Database connected",
  "migrations": "Migration status output..."
}
```

## Security Considerations

⚠️ **Important:** The migration endpoints are publicly accessible by default. For production:

1. **Set MIGRATION_SECRET_TOKEN** in Vercel environment variables
2. **Use the token** when calling the migration endpoint
3. **Remove or protect the routes** after initial setup if desired

To remove the migration routes after setup, delete or comment out these lines in `routes/web.php`:
```php
Route::post('/migrate', [\App\Http\Controllers\MigrationController::class, 'migrate'])->name('migrate');
Route::get('/migrate/status', [\App\Http\Controllers\MigrationController::class, 'status'])->name('migrate.status');
```

## Troubleshooting

### Migration Fails During Build

This is normal and expected. The build will continue, and you can run migrations manually using one of the methods above.

### "Database connection failed" Error

1. Verify your database credentials in Vercel:
   ```bash
   vercel env ls
   ```

2. Check that your database allows connections from Vercel's IP ranges
3. Ensure SSL/TLS is properly configured if required

### "Table already exists" Errors

This is normal if migrations have already been run. Laravel migrations are idempotent and safe to run multiple times.

## Quick Start

After deploying to Vercel:

1. **Set migration token** (recommended):
   ```bash
   vercel env add MIGRATION_SECRET_TOKEN production
   ```

2. **Run migrations**:
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate?token=YOUR_TOKEN"
   ```

3. **Verify status**:
   ```bash
   curl "https://dentalschedule.vercel.app/migrate/status"
   ```

4. **Test your site**:
   Visit: https://dentalschedule.vercel.app

## Required Tables

After running migrations, your database should have these tables:
- `users` - User accounts
- `sessions` - Session storage
- `cache` - Cache storage
- `cache_locks` - Cache locking
- `jobs` - Queue jobs
- `patients` - Patient records
- `appointments` - Appointment records
- And other application-specific tables

## Next Steps

After migrations are complete:
1. Verify your site is working: https://dentalschedule.vercel.app
2. Check Vercel logs if you encounter any issues: `vercel logs https://dentalschedule.vercel.app`
3. Consider removing or protecting the migration endpoints after initial setup

