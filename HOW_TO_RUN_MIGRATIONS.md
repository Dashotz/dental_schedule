# How to Run Migrations

## Option 1: Using Command Prompt or PowerShell (Windows)

1. **Open Command Prompt or PowerShell:**
   - Press `Win + R`
   - Type `cmd` or `powershell`
   - Press Enter

2. **Run the command:**
   ```bash
   curl -X POST "https://dentalschedule.vercel.app/migrate"
   ```

3. **If curl doesn't work**, use PowerShell's `Invoke-WebRequest`:
   ```powershell
   Invoke-WebRequest -Uri "https://dentalschedule.vercel.app/migrate" -Method POST
   ```

## Option 2: Using a Web Browser (Easier!)

Since the migration endpoint is a POST request, you can't just visit it in a browser. But you can use:

### Browser Extension:
- **Postman** (Chrome/Firefox extension)
- **REST Client** (VS Code extension)
- **Thunder Client** (VS Code extension)

### Or use an online tool:
- **https://reqbin.com/** - Online REST client
- **https://httpie.io/app** - Online HTTP client

## Option 3: Using VS Code REST Client Extension

1. Install "REST Client" extension in VS Code
2. Create a file: `migrate.http`
3. Add this content:
   ```
   POST https://dentalschedule.vercel.app/migrate
   ```
4. Click "Send Request" above the line

## Option 4: Using Postman

1. Download Postman: https://www.postman.com/downloads/
2. Create a new request
3. Set method to **POST**
4. Enter URL: `https://dentalschedule.vercel.app/migrate`
5. Click **Send**

## Option 5: Using PowerShell (Windows - Recommended)

Open PowerShell and run:

```powershell
Invoke-WebRequest -Uri "https://dentalschedule.vercel.app/migrate" -Method POST
```

Or if you want to see the response:

```powershell
$response = Invoke-WebRequest -Uri "https://dentalschedule.vercel.app/migrate" -Method POST
$response.Content
```

## Quick Test

First, let's test if the endpoint is accessible:

```powershell
# Test the status endpoint (GET request - works in browser)
Invoke-WebRequest -Uri "https://dentalschedule.vercel.app/migrate/status"
```

You can also visit this in your browser:
https://dentalschedule.vercel.app/migrate/status

## Recommended: Use PowerShell

Since you're on Windows, PowerShell is the easiest:

1. Open PowerShell
2. Copy and paste this command:
   ```powershell
   Invoke-WebRequest -Uri "https://dentalschedule.vercel.app/migrate" -Method POST
   ```
3. Press Enter
4. You should see a response indicating success or any errors

## What to Expect

If successful, you'll see something like:
```json
{
  "success": true,
  "message": "Migrations completed successfully",
  "output": "..."
}
```

If there's an error, you'll see details about what went wrong.

