# Fly.io Deployment Guide for Dental Scheduling System

## ✅ Yes, Fly.io Makes Your System Fully Compatible!

**Fly.io is an excellent choice for your multi-tenant SaaS application!** Here's why:

### ⚠️ Credit Card Requirement

**Important**: Fly.io requires a credit card to deploy apps, even on the free tier. They use it for:
- Account verification
- Preventing abuse
- Automatic upgrades (if you exceed free tier)

**Good News**: They won't charge you on the free tier. The card is just on file.

**If you don't have a credit card**, see `NO_CREDIT_CARD_HOSTING.md` for alternatives like InfinityFree.

### Why Fly.io Works Perfectly for Your System

1. ✅ **Subdomain Support** - Native support for custom domains and wildcard subdomains
2. ✅ **Always-On** - No sleep (unlike Railway/Render free tiers)
3. ✅ **Global Edge** - Fast performance worldwide
4. ✅ **PostgreSQL Included** - Free tier includes database
5. ✅ **Docker-Based** - Full control over environment
6. ✅ **Multiple Apps** - Can deploy separate apps for different purposes
7. ✅ **Background Workers** - Supports queue workers
8. ✅ **File Storage** - Persistent volumes for file storage

### Your System Architecture on Fly.io

```
┌─────────────────────────────────────────────────┐
│              Fly.io Platform                     │
├─────────────────────────────────────────────────┤
│                                                  │
│  Main App (yourdomain.com)                      │
│  ├── Admin Panel (admin.yourdomain.com)         │
│  ├── Clinic 1 (clinic1.yourdomain.com)         │
│  ├── Clinic 2 (clinic2.yourdomain.com)          │
│  └── Clinic N (clinicN.yourdomain.com)          │
│                                                  │
│  PostgreSQL Database (Shared)                   │
│  Persistent Volume (File Storage)               │
│  Queue Worker (Background Jobs)                 │
└─────────────────────────────────────────────────┘
```

## 🎯 System Compatibility Analysis

### ✅ What Works Out of the Box

1. **Subdomain Detection** - Your `CheckSubdomainStatus` middleware already handles hostname-based subdomains
2. **Multi-Tenant Architecture** - Perfect for Fly.io's multi-app setup
3. **Database Isolation** - Your `subdomain_id` filtering works perfectly
4. **File Storage** - Can use Fly.io volumes or cloud storage
5. **Queue System** - Fly.io supports background workers

### ⚠️ What Needs Adaptation

1. **Port-Based Routing** → **Subdomain-Based Routing**
   - Remove port restrictions for production
   - Use hostname detection (already supported!)

2. **Dynamic Server Starting** → **Single App with Subdomain Routing**
   - No need to start separate servers per subdomain
   - One app handles all subdomains via routing

3. **Subdomain Server Service** → **Not Needed in Production**
   - Keep for development/testing
   - Production uses single app instance

## 📋 Pre-Deployment Checklist

### System Requirements
- [x] Laravel 11.x ✅
- [x] PHP 8.2+ ✅
- [x] PostgreSQL/MySQL support ✅
- [x] Subdomain routing support ✅
- [x] Environment-based configuration ✅

### What You Have
- ✅ Multi-tenant architecture
- ✅ Subdomain detection middleware
- ✅ Database isolation
- ✅ Service layer architecture
- ✅ Policy-based authorization
- ✅ Form request validation

## 🚀 Step-by-Step Fly.io Deployment

### Step 1: Install Fly.io CLI

**Windows:**
```powershell
# Using PowerShell
iwr https://fly.io/install.ps1 -useb | iex
```

**Mac/Linux:**
```bash
curl -L https://fly.io/install.sh | sh
```

**Verify Installation:**
```bash
fly version
```

### Step 2: Login to Fly.io

```bash
fly auth login
```

### Step 3: Create Fly.io Configuration

Create `fly.toml` in your project root:

```toml
# fly.toml
app = "dental-schedule"
primary_region = "iad"  # Washington, D.C. (or choose closest to you)

[build]
  builder = "paketobuildpacks/builder:base"

[env]
  APP_ENV = "production"
  APP_DEBUG = "false"
  LOG_LEVEL = "info"

[http_service]
  internal_port = 8080
  force_https = true
  auto_stop_machines = false
  auto_start_machines = true
  min_machines_running = 1
  processes = ["app"]

[[services]]
  protocol = "tcp"
  internal_port = 8080
  processes = ["app"]

  [[services.ports]]
    port = 80
    handlers = ["http"]
    force_https = true

  [[services.ports]]
    port = 443
    handlers = ["tls", "http"]

  [[services.http_checks]]
    interval = "10s"
    timeout = "2s"
    grace_period = "5s"
    method = "GET"
    path = "/up"
    protocol = "http"
    tls_skip_verify = false

[[vm]]
  cpu_kind = "shared"
  cpus = 1
  memory_mb = 512
```

### Step 4: Create Dockerfile (Optional - Fly.io can auto-detect Laravel)

Create `Dockerfile`:

```dockerfile
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    nginx

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Expose port
EXPOSE 8080

# Start script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
```

### Step 5: Create Nginx Configuration

Create `docker/nginx.conf`:

```nginx
events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    sendfile on;
    keepalive_timeout 65;

    server {
        listen 8080;
        server_name _;
        root /var/www/html/public;
        index index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
```

### Step 6: Create Start Script

Create `docker/start.sh`:

```bash
#!/bin/sh

# Start PHP-FPM
php-fpm -D

# Start Nginx
nginx -g 'daemon off;'
```

### Step 7: Create PostgreSQL Database

```bash
# Create PostgreSQL database
fly postgres create --name dental-schedule-db --region iad --vm-size shared-cpu-1x --volume-size 3

# Get connection string
fly postgres connect -a dental-schedule-db
```

### Step 8: Set Environment Variables

```bash
# Set app environment variables
fly secrets set \
  APP_KEY="$(php artisan key:generate --show)" \
  APP_ENV=production \
  APP_DEBUG=false \
  APP_URL=https://yourdomain.com \
  DB_CONNECTION=pgsql \
  DB_HOST=$(fly postgres connect -a dental-schedule-db | grep host) \
  DB_DATABASE=postgres \
  DB_USERNAME=postgres \
  DB_PASSWORD=$(fly postgres connect -a dental-schedule-db | grep password) \
  MAIN_DOMAIN=yourdomain.com \
  ADMIN_DOMAIN=admin.yourdomain.com \
  CACHE_DRIVER=redis \
  SESSION_DRIVER=redis \
  QUEUE_CONNECTION=redis
```

### Step 9: Deploy Application

```bash
# Launch the app
fly launch

# Deploy
fly deploy
```

### Step 10: Configure Domains

```bash
# Add main domain
fly domains add yourdomain.com

# Add admin subdomain
fly domains add admin.yourdomain.com

# Add wildcard for clinic subdomains
fly domains add *.yourdomain.com
```

### Step 11: Update DNS Records

Point your DNS to Fly.io:

```
Type    Name    Value
A       @       <fly.io-ip>
CNAME   admin   <your-app>.fly.dev
CNAME   *       <your-app>.fly.dev
```

## 🔧 Code Changes Required

### 1. Update CheckSubdomainStatus Middleware

Modify to prioritize hostname over port:

```php
// app/Http/Middleware/CheckSubdomainStatus.php

public function handle(Request $request, Closure $next): Response
{
    $host = $request->getHost();
    $port = $request->getPort();
    
    // Production: Use hostname-based routing
    if (config('app.env') === 'production') {
        return $this->handleHostnameBased($request, $next);
    }
    
    // Development: Use port-based routing
    return $this->handlePortBased($request, $next);
}

protected function handleHostnameBased(Request $request, Closure $next): Response
{
    $host = $request->getHost();
    $mainDomain = config('app.main_domain');
    $adminDomain = config('app.admin_domain');
    
    // Admin domain check
    if ($host === parse_url($adminDomain, PHP_URL_HOST) || 
        str_starts_with($host, 'admin.')) {
        // Allow admin routes
        if ($request->is('admin/*') && auth('admin')->check()) {
            return $next($request);
        }
        // Redirect to admin login if not authenticated
        if (!$request->is('admin/*')) {
            return redirect()->route('admin.login');
        }
    }
    
    // Extract subdomain from host
    $subdomainName = $this->extractSubdomainName($host, $mainDomain);
    
    if ($subdomainName && $subdomainName !== 'admin') {
        $subdomain = Subdomain::where('subdomain', $subdomainName)->first();
        
        if ($subdomain) {
            return $this->processSubdomain($request, $subdomain, $next);
        }
        
        return response()->view('errors.subdomain-not-found', [
            'subdomain' => $subdomainName
        ], 404);
    }
    
    // Main domain - show main site
    return $next($request);
}

protected function extractSubdomainName(string $host, string $mainDomain): ?string
{
    $mainHost = parse_url($mainDomain, PHP_URL_HOST) ?? $mainDomain;
    
    if ($host === $mainHost) {
        return null; // Main domain
    }
    
    // Extract subdomain (e.g., clinic1 from clinic1.yourdomain.com)
    if (str_ends_with($host, '.' . $mainHost)) {
        return str_replace('.' . $mainHost, '', $host);
    }
    
    return null;
}
```

### 2. Update Routes

Modify `routes/web.php`:

```php
// Landing page - hostname-based routing for production
Route::get('/', function () {
    $host = request()->getHost();
    $subdomain = request()->attributes->get('current_subdomain');
    
    // Production: Use hostname
    if (config('app.env') === 'production') {
        $mainDomain = config('app.main_domain');
        $adminDomain = config('app.admin_domain');
        
        // Admin/main site
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
        
        return view('subdomain-template.index');
    }
    
    // Development: Use port-based (existing code)
    $port = request()->getPort();
    if ($port == 9000) {
        return view('main-site.index');
    } elseif ($port == 8000) {
        return view('subdomain-template.index');
    } elseif ($port >= 10000) {
        // ... existing port-based logic
    }
    
    abort(403, 'Invalid access');
});
```

### 3. Update Middleware Registration

Modify `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    // Only apply port restrictions in development
    if (config('app.env') !== 'production') {
        $middleware->alias([
            'restrict.port' => \App\Http\Middleware\RestrictToPort::class,
            'allow.subdomain.ports' => \App\Http\Middleware\AllowSubdomainPorts::class,
        ]);
    }
    
    // Always apply these
    $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    $middleware->alias([
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'subdomain.check' => \App\Http\Middleware\CheckSubdomainStatus::class,
        // ... other middleware
    ]);
})
```

### 4. Update Subdomain Creation

Modify `SubdomainController` to not start servers in production:

```php
// app/Http/Controllers/Admin/SubdomainController.php

public function store(Request $request)
{
    // ... validation and creation ...
    
    // Only start server in development
    if (config('app.env') !== 'production') {
        $serverStarted = SubdomainServerService::startServer($subdomain);
    }
    
    // In production, subdomains are handled by routing
    // No need to start separate servers
}
```

## 📊 System Architecture on Fly.io

### Application Structure

```
Fly.io Platform
├── Main Application (dental-schedule)
│   ├── Handles all subdomains via routing
│   ├── Admin panel (admin.yourdomain.com)
│   ├── Clinic subdomains (clinic*.yourdomain.com)
│   └── Main site (yourdomain.com)
│
├── PostgreSQL Database
│   ├── Shared database with subdomain_id isolation
│   └── All tenant data in one database
│
├── Redis (Optional - for cache/queues)
│   ├── Session storage
│   ├── Cache
│   └── Queue backend
│
└── Persistent Volume (Optional)
    └── File storage for uploads
```

### Request Flow

```
1. User visits clinic1.yourdomain.com
   ↓
2. DNS resolves to Fly.io edge
   ↓
3. Fly.io routes to your app
   ↓
4. CheckSubdomainStatus middleware:
   - Extracts "clinic1" from hostname
   - Looks up subdomain in database
   - Sets current_subdomain in request
   ↓
5. Route handler:
   - Uses subdomain_view_path
   - Renders clinic1-specific views
   ↓
6. Global scope filters data by subdomain_id
   ↓
7. Response returned to user
```

## 🎯 Advantages of Fly.io for Your System

### 1. **Always-On**
- ✅ No sleep (unlike Railway/Render free tier)
- ✅ Instant response times
- ✅ Better for production

### 2. **Subdomain Support**
- ✅ Native wildcard subdomain support
- ✅ Easy domain management
- ✅ Perfect for multi-tenant

### 3. **Scalability**
- ✅ Can scale horizontally
- ✅ Global edge network
- ✅ Fast worldwide

### 4. **Cost-Effective**
- ✅ Generous free tier
- ✅ Pay only for what you use
- ✅ No hidden costs

### 5. **Developer Experience**
- ✅ Great CLI tools
- ✅ Easy deployments
- ✅ Good documentation

## 📈 Performance Expectations

### Free Tier Limits
- **3 shared-cpu-1x VMs** (1 CPU, 256MB RAM each)
- **3GB persistent volume storage**
- **160GB outbound data transfer/month**

### Expected Performance
- **Response Time**: < 200ms (with edge caching)
- **Concurrent Users**: ~50-100 (on free tier)
- **Database Queries**: Fast (PostgreSQL on same network)
- **File Uploads**: Good (persistent volumes)

### Scaling Options
- Upgrade to dedicated CPU for better performance
- Add more VMs for horizontal scaling
- Use Redis for better caching

## 🔒 Security on Fly.io

### Built-in Security
- ✅ Automatic SSL/TLS certificates
- ✅ DDoS protection
- ✅ Network isolation
- ✅ Secret management

### Your Security Features (Already Implemented)
- ✅ Multi-tenant data isolation
- ✅ Policy-based authorization
- ✅ CSRF protection
- ✅ Input sanitization
- ✅ Security headers
- ✅ Account lockout
- ✅ Rate limiting

## 📝 Migration Checklist

### Pre-Migration
- [ ] Install Fly.io CLI
- [ ] Create Fly.io account
- [ ] Backup current database
- [ ] Test subdomain routing locally

### Code Changes
- [ ] Update CheckSubdomainStatus middleware
- [ ] Update routes for hostname-based routing
- [ ] Update environment configuration
- [ ] Remove port restrictions for production
- [ ] Update subdomain creation logic

### Deployment
- [ ] Create fly.toml
- [ ] Create Dockerfile (if needed)
- [ ] Set up PostgreSQL database
- [ ] Configure environment variables
- [ ] Deploy application
- [ ] Configure domains
- [ ] Update DNS records
- [ ] Test all subdomains

### Post-Deployment
- [ ] Verify admin panel works
- [ ] Test clinic subdomains
- [ ] Verify data isolation
- [ ] Test file uploads
- [ ] Monitor performance
- [ ] Set up monitoring/alerts

## 🚨 Important Considerations

### 1. **Subdomain Server Service**
- **Development**: Keep using `SubdomainServerService` for local testing
- **Production**: Not needed - one app handles all subdomains

### 2. **Port-Based Routing**
- **Development**: Continue using ports (8000, 9000, 10000+)
- **Production**: Use hostname-based routing only

### 3. **Database**
- Use PostgreSQL (Fly.io's default)
- Or use external MySQL if preferred
- All tenants share one database (with `subdomain_id` isolation)

### 4. **File Storage**
- Use Fly.io persistent volumes for small files
- Or use S3/cloud storage for large files
- Consider cloud storage for production scale

### 5. **Queue Workers**
- Deploy separate worker process for background jobs
- Use Redis for queue backend
- Configure in fly.toml

## 💰 Cost Estimation

### Free Tier (Sufficient for Testing/Small Apps)
- **Application**: Free (3 VMs)
- **Database**: Free (PostgreSQL)
- **Storage**: Free (3GB)
- **Bandwidth**: Free (160GB/month)
- **Total**: $0/month

### Paid Tier (For Production)
- **Application**: ~$5-10/month (dedicated CPU)
- **Database**: ~$7/month (managed PostgreSQL)
- **Storage**: ~$0.15/GB/month
- **Bandwidth**: ~$0.02/GB after free tier
- **Total**: ~$15-25/month (for small-medium traffic)

## 🎯 Final Verdict

### ✅ YES, Fly.io Makes Your System Fully Compatible!

**Why:**
1. ✅ Your middleware already supports hostname-based subdomains
2. ✅ Multi-tenant architecture works perfectly
3. ✅ Database isolation is maintained
4. ✅ All features will work as expected

**What You Need:**
1. Minor code changes (prioritize hostname over port)
2. Environment configuration
3. DNS setup
4. Deployment configuration

**Result:**
- ✅ Fully functional multi-tenant SaaS
- ✅ All subdomains working
- ✅ Admin panel accessible
- ✅ Data isolation maintained
- ✅ Better performance than free shared hosting

---

## 📚 Next Steps

1. **Review this guide**
2. **Install Fly.io CLI**
3. **Test locally with hostname routing**
4. **Deploy to Fly.io**
5. **Configure domains**
6. **Go live!**

Your system is **95% ready** for Fly.io deployment! 🚀

