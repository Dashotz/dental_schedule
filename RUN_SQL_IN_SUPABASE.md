# Run SQL Script in Supabase

## Step-by-Step Instructions

### 1. Open Supabase SQL Editor

1. Go to your Supabase dashboard: https://app.supabase.com
2. Select your project: **dental-schedule**
3. In the **left sidebar**, click **"SQL Editor"** (it has a code/terminal icon)
4. Click **"New query"** button (top right)

### 2. Copy the SQL Script

1. Open the file: `database/migrations-sql-postgres.sql` in your project
2. **Select all** the SQL code (Ctrl+A or Cmd+A)
3. **Copy** it (Ctrl+C or Cmd+C)

### 3. Paste and Run in Supabase

1. In the Supabase SQL Editor, **paste** the SQL code (Ctrl+V or Cmd+V)
2. Click the **"Run"** button (or press `Ctrl+Enter` / `Cmd+Enter`)
3. Wait a few seconds for it to execute

### 4. Verify Tables Were Created

1. In Supabase dashboard, click **"Table Editor"** in the left sidebar
2. You should see these tables:
   - `sessions`
   - `cache`
   - `cache_locks`
   - `jobs`
   - `job_batches`
   - `failed_jobs`
   - `users`
   - `migrations`

### 5. Test Your Site

1. Visit: https://dentalschedule.vercel.app
2. If you see the welcome page (not a 500 error), it's working! ✅

### 6. Run Full Migrations (Optional)

After the basic tables are created, you can run all Laravel migrations:

```bash
curl -X POST "https://dentalschedule.vercel.app/migrate"
```

Or use a tool like Postman to make a POST request to:
`https://dentalschedule.vercel.app/migrate`

## What the SQL Script Does

The script creates these essential tables:
- **sessions** - For user sessions
- **cache** - For application caching
- **cache_locks** - For cache locking
- **jobs** - For queue jobs
- **job_batches** - For batch jobs
- **failed_jobs** - For failed queue jobs
- **users** - For user accounts
- **migrations** - To track which migrations have run

## Troubleshooting

### "Table already exists" errors
- This is fine! It means the tables are already created
- You can ignore these errors

### "Permission denied" error
- Make sure you're using the correct database user (postgres)
- Check that your project is fully set up

### Script doesn't run
- Make sure you copied the entire script
- Check for any syntax errors
- Try running it section by section

## Next Steps

After running the SQL script:
1. ✅ Test your site
2. ✅ Run full migrations (creates all application tables)
3. ✅ Create your first admin user
4. ✅ Start using your application!

Let me know once you've run the SQL script and we'll test the site!

