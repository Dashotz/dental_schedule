# Deploying to Vercel

This Laravel application is configured to deploy on Vercel's serverless platform.

## Prerequisites

1. **Vercel Account**: Sign up at [vercel.com](https://vercel.com)
2. **Vercel CLI**: Install globally with `npm install -g vercel`
3. **External Database**: Vercel doesn't support traditional databases. You'll need:
   - A managed MySQL/PostgreSQL service (e.g., PlanetScale, Supabase, AWS RDS)
   - Or use a database-as-a-service provider

## Deployment Steps

### 1. Install Vercel CLI (if not already installed)

```bash
npm install -g vercel
```

### 2. Login to Vercel

```bash
vercel login
```

### 3. Configure Environment Variables

Before deploying, you need to set up environment variables in Vercel:

**Required Environment Variables:**
- `APP_KEY` - Generate with: `php artisan key:generate --show`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` - Your Vercel deployment URL (e.g., `https://your-app.vercel.app`)
- `DB_CONNECTION=mysql` (or `pgsql`)
- `DB_HOST` - Your external database host
- `DB_PORT` - Database port (usually 3306 for MySQL, 5432 for PostgreSQL)
- `DB_DATABASE` - Your database name
- `DB_USERNAME` - Database username
- `DB_PASSWORD` - Database password
- `SESSION_DRIVER=database` (recommended for serverless)
- `CACHE_STORE=database` (recommended for serverless)
- `QUEUE_CONNECTION=database`

**Optional but Recommended:**
- `REDIS_HOST` - If using Redis for caching
- `REDIS_PASSWORD` - Redis password
- `MAIL_*` - Email configuration if sending emails

### 4. Set Environment Variables in Vercel

You can set them via:
- **Vercel Dashboard**: Go to your project → Settings → Environment Variables
- **Vercel CLI**: `vercel env add <VARIABLE_NAME>`

### 5. Deploy

**First Deployment:**
```bash
vercel
```

Follow the prompts to:
- Link to existing project or create new
- Set up project settings
- Deploy

**Subsequent Deployments:**
```bash
vercel --prod
```

### 6. Run Migrations

After first deployment, run migrations:
```bash
vercel env pull .env.production
php artisan migrate --force
```

Or use Vercel's build command to run migrations automatically (see below).

## Build Configuration

The `vercel.json` file is already configured with:
- PHP serverless function at `api/index.php`
- Static file serving from `public/` directory
- Proper routing for Laravel routes

## Important Considerations

### Database
- **Use External Database**: Vercel's serverless functions can't host a database
- **Connection Pooling**: Consider using connection pooling for better performance
- **SSL/TLS**: Ensure your database connection uses SSL

### File Storage
- **Ephemeral Storage**: Vercel functions have ephemeral storage
- **Use Cloud Storage**: For file uploads, use S3, Google Cloud Storage, or similar
- **Update `config/filesystems.php`**: Configure cloud disk for production

### Sessions & Caching
- **Database Sessions**: Already configured with `SESSION_DRIVER=database`
- **Database Cache**: Configured with `CACHE_STORE=database`
- **Alternative**: Use Redis if you have an external Redis instance

### Queue Jobs
- **Database Queue**: Configured with `QUEUE_CONNECTION=database`
- **Note**: Vercel doesn't support long-running queue workers
- **Alternative**: Use external queue service (Laravel Horizon on separate server, or use Vercel Cron Jobs)

### Storage Directories
Make sure these directories are writable:
- `storage/framework/cache`
- `storage/framework/sessions`
- `storage/framework/views`
- `storage/logs`

Vercel's serverless environment handles this automatically, but ensure your code doesn't rely on persistent file storage.

## Troubleshooting

### Database Connection Issues
- Verify database credentials in Vercel environment variables
- Check if your database allows connections from Vercel's IP ranges
- Ensure SSL is properly configured

### 500 Errors
- Check Vercel function logs in the dashboard
- Verify all environment variables are set
- Ensure `APP_KEY` is generated and set

### Static Assets Not Loading
- Verify routes in `vercel.json` are correct
- Check that assets are in the `public/` directory
- Ensure build process compiles assets correctly

## Automated Migrations (Optional)

To run migrations automatically on deploy, you can add a build command in `vercel.json`:

```json
{
  "buildCommand": "composer install --no-dev --optimize-autoloader && php artisan migrate --force"
}
```

However, this is not recommended for production as it runs on every deployment. Instead, run migrations manually or use a separate deployment workflow.

## Custom Domain

1. Go to Vercel Dashboard → Your Project → Settings → Domains
2. Add your custom domain
3. Update `APP_URL` environment variable to match your domain
4. Follow DNS configuration instructions

## Support

For more information:
- [Vercel Documentation](https://vercel.com/docs)
- [Laravel Documentation](https://laravel.com/docs)

