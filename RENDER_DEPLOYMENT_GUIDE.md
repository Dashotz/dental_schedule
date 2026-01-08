# Render.com Deployment Guide for Laravel Dental Scheduling System

## 🎯 What to Choose on Render

Based on your Laravel application, here's what you need:

---

## ✅ Required Services

### 1. **Web Services** ⭐ (PRIMARY - Choose This First!)

**What it is:** Dynamic web app hosting for your Laravel application

**Why you need it:**
- ✅ Hosts your main Laravel application
- ✅ Handles all HTTP requests
- ✅ Runs PHP and Laravel
- ✅ Perfect for full-stack apps

**Action:** Click **"New Web Service →"**

**Configuration:**
- **Language:** Select **"Docker"** (PHP is not directly available, use Docker instead)
- **Build Command:** (Leave empty - Docker handles this)
- **Start Command:** (Leave empty - Docker handles this)
- **Plan:** Free (for testing) or Starter ($7/month for production)

**Important:** Render doesn't have PHP as a direct language option. You must use **Docker** to deploy Laravel.

---

### 2. **Postgres** ⭐ (REQUIRED - Database)

**What it is:** PostgreSQL database for your application data

**Why you need it:**
- ✅ Stores all your application data
- ✅ Patients, appointments, subdomains, etc.
- ✅ Required for Laravel to work

**Action:** Click **"New Postgres →"**

**Configuration:**
- **Plan:** Free (90 days) or Starter ($7/month after)
- **Database Name:** `dental_schedule` (or your choice)
- **Note:** Free tier expires after 90 days, then $7/month

**Alternative:** You can use MySQL if you prefer, but Postgres is recommended.

---

## 🔧 Optional Services (Recommended)

### 3. **Background Workers** (Optional but Recommended)

**What it is:** Long-lived services for processing async tasks

**Why you might need it:**
- ✅ Process Laravel queues
- ✅ Send emails in background
- ✅ Handle heavy tasks asynchronously

**Action:** Click **"New Worker →"** (only if you use Laravel queues)

**Configuration:**
- **Start Command:** `php artisan queue:work --tries=3`
- **Plan:** Free (for testing)

**Note:** Only create this if you use Laravel queues. If not, skip it.

---

### 4. **Key Value (Redis)** (Optional but Recommended)

**What it is:** Redis-compatible cache and queue backend

**Why you might need it:**
- ✅ Faster caching than file-based cache
- ✅ Better queue performance
- ✅ Session storage

**Action:** Click **"New Key Value Instance →"** (optional)

**Configuration:**
- **Plan:** Free (for testing)
- **Use for:** Cache, sessions, queues

**Note:** Not required if you use file-based cache. But recommended for better performance.

---

### 5. **Cron Jobs** (Optional)

**What it is:** Scheduled tasks that run periodically

**Why you might need it:**
- ✅ Run Laravel scheduled tasks
- ✅ Daily backups
- ✅ Cleanup tasks

**Action:** Click **"New Cron Job →"** (only if you have scheduled tasks)

**Configuration:**
- **Schedule:** `*/5 * * * *` (every 5 minutes for Laravel scheduler)
- **Command:** `php artisan schedule:run`
- **Plan:** Free

**Note:** Laravel has a built-in scheduler. You can use this or run `php artisan schedule:run` in a worker.

---

## 📋 Step-by-Step: What to Choose

### Step 1: Create Database First
1. Click **"New Postgres →"**
2. Name: `dental-schedule-db`
3. Plan: **Free** (for testing)
4. Region: Choose closest to you
5. Click **"Create Database"**

### Step 2: Create Web Service (Main App)
1. Click **"New Web Service →"**
2. Connect your GitHub repository
3. Configure:
   - **Name:** `dental-schedule-app`
   - **Environment:** PHP
   - **Build Command:** `composer install --no-dev --optimize-autoloader && php artisan optimize`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
   - **Plan:** **Free** (for testing)
4. Add environment variables (see below)
5. Click **"Create Web Service"**

### Step 3: (Optional) Create Background Worker
1. Click **"New Worker →"**
2. Same repository as Web Service
3. Configure:
   - **Name:** `dental-schedule-worker`
   - **Start Command:** `php artisan queue:work --tries=3`
   - **Plan:** **Free**
4. Add same environment variables
5. Click **"Create Worker"**

### Step 4: (Optional) Create Redis Cache
1. Click **"New Key Value Instance →"**
2. Name: `dental-schedule-redis`
3. Plan: **Free**
4. Click **"Create"**

---

## 🔐 Environment Variables to Set

In your **Web Service** settings, add these environment variables:

```env
APP_NAME="Dental Schedule"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://your-app-name.onrender.com

DB_CONNECTION=pgsql
DB_HOST=YOUR_POSTGRES_HOST
DB_PORT=5432
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USER
DB_PASSWORD=YOUR_DATABASE_PASSWORD

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIN_DOMAIN=yourdomain.com
ADMIN_DOMAIN=admin.yourdomain.com
BASE_DOMAIN=yourdomain.com
```

**Important:** 
- Get database credentials from your Postgres service
- Generate `APP_KEY` using: `php artisan key:generate --show`
- Update `APP_URL` with your Render service URL

---

## 🎯 Quick Answer: What to Choose NOW

### ⚠️ Important: Render Doesn't Support PHP Directly!

**You must use Docker** to deploy Laravel on Render.

### Minimum Setup (Just to Get Started):

1. ✅ **Web Services** → Click "New Web Service →"
   - **Language:** Select **"Docker"** (not PHP - it's not available)
2. ✅ **Postgres** → Click "New Postgres →"

**Before deploying, you need to:**
- Create `Dockerfile` in your repository (see Docker Setup section below)
- Create `docker/nginx.conf` and `docker/supervisord.conf`
- Commit and push to GitHub

**That's it!** You can add other services later.

### Recommended Setup (For Production):

1. ✅ **Web Services** → Main application
2. ✅ **Postgres** → Database
3. ✅ **Background Workers** → Queue processing
4. ✅ **Key Value (Redis)** → Caching
5. ⚠️ **Cron Jobs** → Scheduled tasks (or use worker)

---

## 💰 Cost Breakdown

### Free Tier:
- **Web Service:** Free (sleeps after 15 min inactivity)
- **Postgres:** Free for 90 days, then $7/month
- **Background Worker:** Free (sleeps after inactivity)
- **Redis:** Free
- **Cron Jobs:** Free

### After 90 Days:
- **Web Service:** Still free (but sleeps)
- **Postgres:** $7/month (required)
- **Total:** ~$7/month minimum

---

## 🚀 Next Steps After Creating Services

1. **Set up environment variables** in Web Service
2. **Run migrations:** Use Render Shell or add to build command
3. **Configure custom domain** (if you have one)
4. **Test the application**
5. **Set up background worker** (if needed)
6. **Configure Redis** (if created)

---

## 📝 Summary

**Start with:**
1. ✅ **Web Services** (your main Laravel app)
2. ✅ **Postgres** (your database)

**Add later if needed:**
- Background Workers (for queues)
- Redis (for better caching)
- Cron Jobs (for scheduled tasks)

**Click "New Web Service →" first!** 🚀

