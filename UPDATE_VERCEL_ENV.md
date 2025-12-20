# Update Vercel Environment Variables for Supabase

## Quick Method: Via Vercel Dashboard

1. **Go to your Vercel project settings:**
   https://vercel.com/francis-cruzs-projects/dental_schedule/settings/environment-variables

2. **For each variable below, click the variable name, then click "Edit" or update the value:**

   Update these variables with your Supabase values:

   - **DB_CONNECTION** = `pgsql`
   - **DB_HOST** = `db.gozrmzlgwxkuqxpzevnc.supabase.co`
   - **DB_PORT** = `5432`
   - **DB_DATABASE** = `postgres`
   - **DB_USERNAME** = `postgres`
   - **DB_PASSWORD** = `Dentalclinic1!`

3. **Make sure they're set for "Production" environment**

4. **Save each one**

## Alternative: Via CLI (if you prefer)

If you want to use CLI, you'll need to confirm each removal, then add them back. Here are the commands:

```bash
# Remove old values (confirm with 'y' when prompted)
vercel env rm DB_CONNECTION production
vercel env rm DB_HOST production
vercel env rm DB_PORT production
vercel env rm DB_DATABASE production
vercel env rm DB_USERNAME production
vercel env rm DB_PASSWORD production

# Add new Supabase values
echo "pgsql" | vercel env add DB_CONNECTION production
echo "db.gozrmzlgwxkuqxpzevnc.supabase.co" | vercel env add DB_HOST production
echo "5432" | vercel env add DB_PORT production
echo "postgres" | vercel env add DB_DATABASE production
echo "postgres" | vercel env add DB_USERNAME production
echo "Dentalclinic1!" | vercel env add DB_PASSWORD production
```

## Recommended: Use Dashboard

The dashboard method is easier and faster. Just go to the link above and update each variable!

