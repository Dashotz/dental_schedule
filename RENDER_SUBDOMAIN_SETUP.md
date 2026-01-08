# Render Subdomain Setup Guide

## ✅ Your System is Now Ready for Subdomain-Based Routing!

After the recent updates, your system now supports **both**:
- **Port-based routing** (development - local testing)
- **Hostname-based routing** (production - Render deployment)

## 🎯 How It Works

### Development (Local)
- Port 9000 → Admin panel
- Port 8000 → Generic template
- Port 10000+ → Individual clinic subdomains

### Production (Render)
- `yourdomain.com` or `admin.yourdomain.com` → Admin panel
- `clinic1.yourdomain.com` → Clinic 1
- `clinic2.yourdomain.com` → Clinic 2
- etc.

## 📋 What Changed

1. ✅ **CheckSubdomainStatus Middleware** - Now detects environment and uses appropriate routing
2. ✅ **Routes** - Removed port restrictions for production
3. ✅ **Home Route** - Supports both port and hostname-based routing

## 🚀 Setting Up Subdomains on Render

### Step 1: Deploy Your Application

1. Deploy to Render (see `RENDER_DEPLOYMENT_GUIDE.md`)
2. Your app will be accessible at: `https://your-app-name.onrender.com`

### Step 2: Configure Custom Domain (Optional but Recommended)

1. In Render dashboard, go to your Web Service
2. Click "Settings" → "Custom Domains"
3. Add your main domain: `yourdomain.com`
4. Add admin subdomain: `admin.yourdomain.com`
5. Add wildcard subdomain: `*.yourdomain.com` (for clinic subdomains)

### Step 3: Update DNS Records

Point your DNS to Render:

```
Type    Name    Value
A       @       <render-ip>
CNAME   admin   <your-app-name>.onrender.com
CNAME   *       <your-app-name>.onrender.com
```

### Step 4: Update Environment Variables

In Render, set these environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

MAIN_DOMAIN=yourdomain.com
ADMIN_DOMAIN=admin.yourdomain.com
BASE_DOMAIN=yourdomain.com
PARENT_DOMAIN=yourdomain.com
```

### Step 5: Create Subdomains in Database

When you create a new clinic subdomain in the admin panel:
- The subdomain name will be used (e.g., "clinic1")
- It will be accessible at: `clinic1.yourdomain.com`
- No port assignment needed in production!

## 🎯 Testing Subdomains

### Test Admin Panel
- Visit: `https://admin.yourdomain.com/admin/login`
- Or: `https://yourdomain.com/admin/login`

### Test Clinic Subdomain
- Visit: `https://clinic1.yourdomain.com`
- Should show clinic1-specific content

### Test Main Site
- Visit: `https://yourdomain.com`
- Should show main site landing page

## ⚠️ Important Notes

1. **No Port Assignment in Production**
   - Subdomains don't need ports on Render
   - They're accessed via hostname only

2. **Subdomain Creation**
   - When creating subdomains in admin panel, ports are still assigned (for development)
   - In production, ports are ignored - only hostname matters

3. **Environment Detection**
   - System automatically detects `APP_ENV=production`
   - Switches to hostname-based routing automatically

4. **DNS Propagation**
   - DNS changes can take 24-48 hours
   - Use Render's default URL for testing first

## 🔧 Troubleshooting

### Subdomain Not Working?

1. **Check DNS**: Ensure DNS records are correct
2. **Check Environment**: Verify `APP_ENV=production` in Render
3. **Check Subdomain**: Ensure subdomain exists in database
4. **Check Base Domain**: Verify `BASE_DOMAIN` matches your actual domain

### Admin Panel Not Accessible?

1. **Check Domain**: Ensure accessing via `admin.yourdomain.com` or main domain
2. **Check Routes**: Verify routes are not blocked
3. **Check Middleware**: Check `CheckSubdomainStatus` logs

## ✅ Summary

**Your system is now ready for Render deployment with subdomain support!**

- ✅ Hostname-based routing works in production
- ✅ Port-based routing still works in development
- ✅ Automatic environment detection
- ✅ No code changes needed - just configure DNS and environment variables

**Next Steps:**
1. Deploy to Render
2. Configure custom domains
3. Update DNS
4. Test subdomains!

