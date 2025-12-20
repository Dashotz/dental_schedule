# Production Database Options for Vercel

Since Vercel is serverless, you need a cloud-hosted database that's accessible from the internet. Here are the best options:

## 🏆 Recommended Options

### 1. **PlanetScale** (Best for Laravel/Vercel) ⭐ RECOMMENDED
- **Free tier**: 1 database, 1GB storage, 1 billion row reads/month
- **Pros**: 
  - Serverless MySQL (scales automatically)
  - Built-in connection pooling
  - Branching (like Git for databases)
  - Free SSL
  - Great for serverless apps
- **Cons**: Some MySQL features limited (no foreign keys by default)
- **Pricing**: Free tier available, then $29/month
- **Setup**: Very easy, 5 minutes
- **Website**: https://planetscale.com

### 2. **Supabase** (PostgreSQL)
- **Free tier**: 500MB database, 2GB bandwidth
- **Pros**:
  - PostgreSQL (more features than MySQL)
  - Includes auth, storage, real-time features
  - Great free tier
  - Auto-scaling
- **Cons**: PostgreSQL (need to change Laravel config)
- **Pricing**: Free tier available, then $25/month
- **Website**: https://supabase.com

### 3. **Railway** (MySQL/PostgreSQL)
- **Free tier**: $5 credit/month (enough for small apps)
- **Pros**:
  - Easy setup
  - Supports both MySQL and PostgreSQL
  - Good documentation
- **Cons**: Free tier limited
- **Pricing**: $5/month minimum after free credit
- **Website**: https://railway.app

### 4. **DigitalOcean Managed Databases**
- **Free tier**: None
- **Pros**:
  - Reliable and stable
  - Full MySQL/PostgreSQL support
  - Good performance
- **Cons**: Paid only ($15/month minimum)
- **Pricing**: $15/month for basic MySQL
- **Website**: https://www.digitalocean.com/products/managed-databases

### 5. **AWS RDS** (Amazon Relational Database Service)
- **Free tier**: 750 hours/month for 12 months (t2.micro)
- **Pros**:
  - Enterprise-grade
  - Highly scalable
  - Many options
- **Cons**: Can be complex, costs can add up
- **Pricing**: Free tier for 12 months, then ~$15/month
- **Website**: https://aws.amazon.com/rds

### 6. **Neon** (PostgreSQL)
- **Free tier**: 0.5GB storage, unlimited projects
- **Pros**:
  - Serverless PostgreSQL
  - Auto-scaling
  - Branching feature
  - Great for development
- **Cons**: PostgreSQL (need to change Laravel config)
- **Pricing**: Free tier available, then $19/month
- **Website**: https://neon.tech

### 7. **Render** (PostgreSQL)
- **Free tier**: 90-day free trial, then $7/month
- **Pros**:
  - Easy setup
  - Good documentation
- **Cons**: PostgreSQL only, free tier limited
- **Pricing**: $7/month minimum
- **Website**: https://render.com

## 🎯 My Recommendation

**For your dental schedule app, I recommend PlanetScale** because:
1. ✅ Free tier is generous
2. ✅ Perfect for serverless (Vercel)
3. ✅ MySQL (no code changes needed)
4. ✅ Easy setup
5. ✅ Built-in connection pooling
6. ✅ Scales automatically

## Quick Setup Guide for PlanetScale

### Step 1: Create Account
1. Go to https://planetscale.com
2. Sign up (free)
3. Create a new database

### Step 2: Get Connection Details
1. Click on your database
2. Click "Connect"
3. Copy the connection string (looks like: `mysql://username:password@host/database`)

### Step 3: Set Vercel Environment Variables
```bash
vercel env add DB_HOST production
# Enter the host from PlanetScale (e.g., aws.connect.psdb.cloud)

vercel env add DB_PORT production
# Enter: 3306

vercel env add DB_DATABASE production
# Enter your database name

vercel env add DB_USERNAME production
# Enter your username

vercel env add DB_PASSWORD production
# Enter your password
```

### Step 4: Run Migrations
After setting up PlanetScale, you can:
1. Run the SQL script (`database/migrations-sql.sql`) in PlanetScale's SQL editor
2. Or use the migration endpoint: `POST /migrate`

## Quick Setup Guide for Supabase (PostgreSQL)

If you prefer PostgreSQL:

### Step 1: Create Account
1. Go to https://supabase.com
2. Sign up (free)
3. Create a new project

### Step 2: Get Connection Details
1. Go to Project Settings → Database
2. Copy connection string

### Step 3: Update Laravel Config
Change in `config/database.php` or `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

### Step 4: Set Vercel Environment Variables
```bash
vercel env add DB_CONNECTION production
# Enter: pgsql

vercel env add DB_HOST production
# Enter Supabase host

vercel env add DB_PORT production
# Enter: 5432

vercel env add DB_DATABASE production
# Enter: postgres

vercel env add DB_USERNAME production
# Enter: postgres

vercel env add DB_PASSWORD production
# Enter your password
```

## Comparison Table

| Provider | Type | Free Tier | Ease of Setup | Best For |
|----------|------|-----------|---------------|----------|
| **PlanetScale** | MySQL | ✅ Generous | ⭐⭐⭐⭐⭐ | Serverless apps |
| **Supabase** | PostgreSQL | ✅ Good | ⭐⭐⭐⭐ | Full-featured apps |
| **Railway** | MySQL/PostgreSQL | ⚠️ Limited | ⭐⭐⭐⭐ | Quick setup |
| **DigitalOcean** | MySQL/PostgreSQL | ❌ None | ⭐⭐⭐ | Production apps |
| **AWS RDS** | MySQL/PostgreSQL | ⚠️ 12 months | ⭐⭐ | Enterprise |
| **Neon** | PostgreSQL | ✅ Good | ⭐⭐⭐⭐ | Development |
| **Render** | PostgreSQL | ⚠️ Trial | ⭐⭐⭐⭐ | Simple apps |

## Important Notes

1. **Connection Pooling**: PlanetScale and Supabase have built-in connection pooling, which is essential for serverless
2. **SSL Required**: All cloud databases require SSL connections
3. **IP Whitelisting**: Some providers allow IP whitelisting, but Vercel's IPs change, so use SSL instead
4. **Backups**: Most providers include automatic backups
5. **Scaling**: Serverless databases (PlanetScale, Supabase) scale automatically

## Next Steps

1. **Choose a provider** (I recommend PlanetScale)
2. **Create account and database**
3. **Get connection details**
4. **Set Vercel environment variables**
5. **Run migrations** (SQL script or migration endpoint)
6. **Test your site**

Would you like me to help you set up a specific provider?

