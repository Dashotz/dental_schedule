# Migration Guide: From Port-Based to Subdomain-Based Routing

## 🎯 Good News!

Your application **already supports subdomain-based routing!** 

Looking at `CheckSubdomainStatus.php`, the middleware can detect subdomains from hostnames (lines 74-83). This means you can use free hosting platforms that support subdomains.

## Current Architecture

### Port-Based (Current - Development)
- Port 9000: Main site / Admin panel
- Port 8000: Generic subdomain template
- Port 10000+: Individual clinic subdomains

### Subdomain-Based (Production - Free Hosting)
- `yourdomain.com` or `admin.yourdomain.com`: Main site / Admin panel
- `clinic1.yourdomain.com`: Clinic 1
- `clinic2.yourdomain.com`: Clinic 2
- etc.

## ✅ What Already Works

Your `CheckSubdomainStatus` middleware already handles:
1. ✅ Subdomain detection from hostname
2. ✅ Subdomain validation
3. ✅ Subscription checking
4. ✅ View path routing

## 🔧 What Needs to Change

### 1. Update Route Configuration

**Current (Port-based):**
```php
// routes/web.php
Route::get('/', function () {
    $port = request()->getPort();
    if ($port == 9000) {
        return view('main-site.index');
    } elseif ($port == 8000) {
        return view('subdomain-template.index');
    }
    // ...
});
```

**New (Subdomain-based):**
```php
// routes/web.php
Route::get('/', function () {
    $host = request()->getHost();
    $subdomain = request()->attributes->get('current_subdomain');
    
    // Admin/main site
    if ($host === config('app.main_domain') || str_starts_with($host, 'admin.')) {
        return view('main-site.index');
    }
    
    // Subdomain-specific
    if ($subdomain) {
        $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
        return view($viewPath . '.index');
    }
    
    // Default template
    return view('subdomain-template.index');
});
```

### 2. Update Middleware

**Remove port restrictions:**
- `RestrictToPort` middleware
- `AllowSubdomainPorts` middleware
- Port checks in routes

**Keep subdomain detection:**
- `CheckSubdomainStatus` (already works with hostnames!)

### 3. Update Subdomain Model

**Remove port field dependency:**
```php
// Instead of port-based lookup:
$subdomain = Subdomain::where('port', $port)->first();

// Use hostname-based lookup (already exists):
$subdomain = Subdomain::where('subdomain', $subdomainName)->first();
```

### 4. Update Environment Configuration

**Add to `.env`:**
```env
APP_URL=https://yourdomain.com
MAIN_DOMAIN=yourdomain.com
ADMIN_DOMAIN=admin.yourdomain.com
```

**Update `config/subdomain.php`:**
```php
return [
    'base_domain' => env('MAIN_DOMAIN', 'yourdomain.com'),
    'parent_domain' => env('ADMIN_DOMAIN', 'admin.yourdomain.com'),
    // Remove port-based config
];
```

## 🚀 Platform-Specific Setup

### Railway (Note: Gives you $5 FREE credit/month - you don't pay it!)

1. **Deploy main application**
2. **Configure subdomains:**
   - Add custom domain: `yourdomain.com`
   - Add subdomain: `admin.yourdomain.com`
   - Add wildcard: `*.yourdomain.com` (for clinic subdomains)

3. **Environment variables:**
```env
APP_URL=https://yourdomain.com
MAIN_DOMAIN=yourdomain.com
ADMIN_DOMAIN=admin.yourdomain.com
```

### Render

1. **Deploy main application**
2. **Configure custom domains:**
   - Add `yourdomain.com`
   - Add `admin.yourdomain.com`
   - Add `*.yourdomain.com` (wildcard for clinics)

3. **Update DNS:**
   - Point `yourdomain.com` → Render service
   - Point `*.yourdomain.com` → Render service

### Fly.io

1. **Deploy application**
2. **Add domains:**
```bash
fly domains add yourdomain.com
fly domains add admin.yourdomain.com
fly domains add *.yourdomain.com
```

## 📝 Step-by-Step Migration

### Step 1: Update Routes

Create a new route file or update existing:

```php
// routes/web.php
Route::get('/', function () {
    $host = request()->getHost();
    $subdomain = request()->attributes->get('current_subdomain');
    
    // Check if it's admin/main domain
    $mainDomain = config('app.main_domain', config('app.url'));
    $adminDomain = config('app.admin_domain', 'admin.' . parse_url($mainDomain, PHP_URL_HOST));
    
    if ($host === parse_url($mainDomain, PHP_URL_HOST) || 
        $host === parse_url($adminDomain, PHP_URL_HOST) ||
        str_starts_with($host, 'admin.')) {
        return view('main-site.index');
    }
    
    // Subdomain-specific
    if ($subdomain) {
        $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
        return view($viewPath . '.index');
    }
    
    // Default
    return view('subdomain-template.index');
});
```

### Step 2: Update Middleware

Modify `CheckSubdomainStatus.php` to prioritize hostname over port:

```php
public function handle(Request $request, Closure $next): Response
{
    $host = $request->getHost();
    $port = $request->getPort();
    
    // Priority 1: Check hostname-based subdomain (for production)
    $subdomainName = $this->extractSubdomainFromHost($host);
    if ($subdomainName) {
        $subdomain = Subdomain::where('subdomain', $subdomainName)->first();
        if ($subdomain) {
            // Process subdomain...
            return $this->processSubdomain($request, $subdomain, $next);
        }
    }
    
    // Priority 2: Check port-based (for development)
    if ($port >= 10000) {
        $subdomain = Subdomain::where('port', $port)->first();
        if ($subdomain) {
            return $this->processSubdomain($request, $subdomain, $next);
        }
    }
    
    // Default handling...
}
```

### Step 3: Remove Port Restrictions

Update routes to remove port middleware:

```php
// OLD
Route::middleware(['restrict.port:9000'])->group(function () {
    // Admin routes
});

// NEW
Route::middleware(['check.admin.domain'])->group(function () {
    // Admin routes (check hostname instead of port)
});
```

### Step 4: Create Domain Check Middleware

```php
// app/Http/Middleware/CheckAdminDomain.php
public function handle(Request $request, Closure $next): Response
{
    $host = $request->getHost();
    $adminDomain = config('app.admin_domain');
    
    // Allow if accessing admin domain or main domain
    if ($host === parse_url($adminDomain, PHP_URL_HOST) || 
        str_starts_with($host, 'admin.')) {
        return $next($request);
    }
    
    abort(403, 'Admin access only on admin domain');
}
```

## 🎯 Recommended Approach

### Hybrid Solution (Best of Both Worlds)

Keep both port-based (development) and subdomain-based (production):

```php
// CheckSubdomainStatus.php
public function handle(Request $request, Closure $next): Response
{
    $host = $request->getHost();
    $port = $request->getPort();
    
    // Production: Use hostname
    if (config('app.env') === 'production') {
        return $this->handleHostnameBased($request, $next);
    }
    
    // Development: Use port
    return $this->handlePortBased($request, $next);
}
```

This way:
- ✅ Development: Continue using ports (8000, 9000, 10000+)
- ✅ Production: Use subdomains on free hosting

## 📋 Checklist for Migration

- [ ] Update routes to support hostname-based routing
- [ ] Create domain check middleware
- [ ] Update environment configuration
- [ ] Test subdomain detection
- [ ] Configure DNS for subdomains
- [ ] Update subdomain creation to use hostnames
- [ ] Remove port dependencies from production
- [ ] Test on free hosting platform

## 🔗 Next Steps

1. **Choose a platform** (Railway or Render recommended)
2. **Set up domain/subdomains** on the platform
3. **Update DNS records** to point to the platform
4. **Deploy application**
5. **Test subdomain routing**

## 💡 Pro Tips

1. **Keep port-based for development** - Easier local testing
2. **Use environment variables** - Switch between port/hostname based on env
3. **Test thoroughly** - Subdomain routing is critical for multi-tenant
4. **Monitor logs** - Watch for subdomain detection issues
5. **Backup before migration** - Always have a rollback plan

---

**Your application is already 80% ready for free hosting!** You just need to prioritize hostname-based routing over port-based routing.

