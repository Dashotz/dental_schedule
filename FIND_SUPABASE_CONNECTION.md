# How to Find Supabase Connection Details

## Step-by-Step Guide

### 1. Navigate to Database Settings

In your Supabase dashboard:
1. Look at the **left sidebar**
2. Under **"CONFIGURATION"**, click **"Database"**
3. This will take you to the Database settings page

### 2. Find Connection String

Once you're on the Database settings page:

**Option A: Connection String (Easiest)**
1. Scroll down to find **"Connection string"** section
2. You'll see different connection string formats
3. Look for the one that says **"URI"** or **"Connection pooling"**
4. It will look like:
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres
   ```

**Option B: Individual Values**
1. In the **"Connection string"** section
2. Look for **"Connection parameters"** or **"Connection info"**
3. You'll see:
   - **Host**: `db.xxxxx.supabase.co` (or similar)
   - **Port**: `5432`
   - **Database name**: `postgres`
   - **User**: `postgres`
   - **Password**: (the one you created when setting up the project)

### 3. If You Can't Find It

Sometimes the connection details are in a different location:

1. Go to **Settings** (gear icon) → **Database**
2. Look for **"Connection string"** or **"Connection info"**
3. Or check **"Connection pooling"** section

### 4. Alternative: Use Connection Pooling String

Supabase also provides a connection pooling string which is better for serverless:
1. In Database settings
2. Look for **"Connection pooling"** section
3. Use the **"Session"** or **"Transaction"** mode connection string
4. The host will be different (usually `aws-0-[region].pooler.supabase.com`)

## What You Need

For Vercel environment variables, you need:
- **DB_HOST**: The host part (e.g., `db.xxxxx.supabase.co`)
- **DB_PORT**: `5432`
- **DB_DATABASE**: `postgres`
- **DB_USERNAME**: `postgres`
- **DB_PASSWORD**: Your database password

## Quick Visual Guide

```
Supabase Dashboard
├── Settings (gear icon) ← Click this
│   └── Database ← Click this
│       └── Connection string section ← Look here
│           ├── Host: db.xxxxx.supabase.co
│           ├── Port: 5432
│           ├── Database: postgres
│           ├── User: postgres
│           └── Password: [your password]
```

## Still Can't Find It?

If you still can't see the connection details:
1. Make sure your project is fully created (wait a few minutes if it's new)
2. Try refreshing the page
3. Check if there's a "Show connection string" or "Reveal" button
4. The connection details might be in a collapsible section - look for arrows or "Expand" buttons

Let me know what you see in the Database settings page!

