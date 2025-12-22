# HelioHost Subdomain Setup Guide

This guide explains how to set up the parent domain and subdomains on HelioHost.

## Overview

- **Parent Domain**: `dental-admin.helioho.st` - Controls all subdomains, admin panel access
- **Subdomains**: `dental-imus.helioho.st`, `dental-bacoor.helioho.st`, etc. - Individual clinic sites

## Configuration

### 1. Environment Variables

Add these to your `.env` file:

```env
PARENT_DOMAIN=dental-admin.helioho.st
BASE_DOMAIN=helioho.st
APP_URL=https://dental-admin.helioho.st
```

### 2. HelioHost DNS Setup

#### For Parent Domain (dental-admin.helioho.st):
1. Go to HelioHost cPanel
2. Navigate to **Subdomains**
3. Create subdomain: `dental-admin`
4. Point it to your Laravel application directory (usually `public_html` or your app folder)

#### For Subdomains (dental-imus, dental-bacoor, etc.):
1. In HelioHost cPanel, go to **Subdomains**
2. Create each subdomain:
   - `dental-imus` → Points to same directory as parent
   - `dental-bacoor` → Points to same directory as parent
   - `dental-kawit` → Points to same directory as parent
3. All subdomains should point to the **same directory** as the parent domain

### 3. Database Setup

Create subdomains in the admin panel:

1. Login to `https://dental-admin.helioho.st/admin`
2. Go to **Subdomains Management**
3. Click **Add New Subdomain**
4. For each clinic:
   - **Subdomain**: `dental-imus` (without .helioho.st)
   - **Name**: Clinic name (e.g., "Dental Imus")
   - **Email**: Clinic email
   - **Phone**: Clinic phone
   - **Address**: Clinic address

### 4. How It Works

#### Parent Domain (dental-admin.helioho.st):
- ✅ Full access to admin panel
- ✅ Can create/manage subdomains
- ✅ Can toggle subdomain status
- ✅ Can manage subscriptions
- ✅ Bypasses subdomain checks

#### Subdomains (dental-imus.helioho.st, etc.):
- ✅ Access to clinic website (welcome page)
- ✅ Access to patient registration
- ✅ Access to doctor/staff dashboard (when logged in)
- ❌ **Blocked if**:
  - Subdomain is inactive (`is_active = false`)
  - Subscription has expired
- Shows "Subscription Required" page when blocked

### 5. Controlling Subdomains

From the parent domain admin panel:

1. **Toggle Subdomain Status**:
   - Go to **Subdomains Management**
   - Click **View** on any subdomain
   - Toggle the status switch
   - When disabled, subdomain shows "Service Temporarily Unavailable"

2. **Subscription Management**:
   - Go to **Subscriptions Management**
   - Create subscriptions for subdomains
   - When subscription expires, subdomain shows "Subscription Expired" page
   - Subdomain automatically becomes inaccessible

### 6. Testing

1. **Test Parent Domain**:
   ```
   https://dental-admin.helioho.st/admin
   ```
   Should show admin panel

2. **Test Active Subdomain**:
   ```
   https://dental-imus.helioho.st
   ```
   Should show welcome page

3. **Test Disabled Subdomain**:
   - Disable subdomain in admin panel
   - Visit `https://dental-imus.helioho.st`
   - Should show "Service Temporarily Unavailable" page

4. **Test Expired Subscription**:
   - Let subscription expire or manually expire it
   - Visit subdomain
   - Should show "Subscription Expired" page

## Troubleshooting

### Subdomain not working?
1. Check DNS propagation (can take 24-48 hours)
2. Verify subdomain points to correct directory in HelioHost
3. Check subdomain exists in database
4. Verify subdomain is active in admin panel

### Admin panel not accessible?
1. Verify you're accessing from `dental-admin.helioho.st`
2. Check `PARENT_DOMAIN` in `.env` matches your domain
3. Clear Laravel cache: `php artisan config:clear`

### Subscription page showing incorrectly?
1. Check subdomain has active subscription
2. Verify subscription end date is in the future
3. Check `is_active` status in database

## Security Notes

- Admin panel is **only** accessible from parent domain
- Subdomains cannot access admin routes
- All subdomain checks happen via middleware
- Subscription status is checked on every request

