<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\DoctorLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\RegistrationLinkController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubdomainController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\TeethRecordController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AvailabilityController;
use Illuminate\Support\Facades\Route;

// Landing page - port-specific routing
Route::get('/', function () {
    $port = request()->getPort();
    if ($port == 9000) {
        return view('main-site.index');
    } elseif ($port == 8000) {
        return view('subdomain-template.index');
    } elseif ($port >= 10000) {
        // Port-based subdomain access (10000+)
        // The CheckSubdomainStatus middleware will handle finding the subdomain by port
        $subdomain = request()->attributes->get('current_subdomain');
        if ($subdomain) {
            $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
            return view($viewPath . '.index');
        }
        return view('subdomain-template.index');
    }
    abort(403, 'Invalid port access');
})->middleware('subdomain.check')->name('home');

// Main-site routes (restricted to port 9000 only)
Route::middleware(['restrict.port:9000'])->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login'])
        ->middleware(['throttle:5,1', 'account.lockout']); // 5 attempts per minute + account lockout
    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

// Doctor authentication routes (accessible on port 8000 or subdomain-specific ports 10000+)
Route::middleware(['allow.subdomain.ports'])->group(function () {
    Route::get('/doctor/login', [DoctorLoginController::class, 'showLoginForm'])->name('doctor.login');
    Route::post('/doctor/login', [DoctorLoginController::class, 'login'])
        ->middleware(['throttle:5,1', 'account.lockout']); // 5 attempts per minute + account lockout
    Route::post('/doctor/logout', [DoctorLoginController::class, 'logout'])->name('doctor.logout');
});

// Public routes (accessible on port 8000 or subdomain-specific ports 10000+)
Route::middleware(['allow.subdomain.ports', 'subdomain.check'])->group(function () {

    // Patient registration via special link only
    Route::get('/register/{token}', [RegistrationLinkController::class, 'showRegistrationForm'])
        ->middleware('throttle:10,1') // 10 attempts per minute per IP
        ->name('patient.register');
    Route::post('/register/{token}', [PatientController::class, 'store'])
        ->middleware('throttle:5,1') // 5 submissions per minute per IP
        ->name('patient.store');
    
    // Public availability endpoint for registration form (no auth required)
    Route::get('/availability/slots', [AvailabilityController::class, 'getAvailableSlots'])
        ->middleware('throttle:30,1') // 30 requests per minute
        ->name('availability.slots.public');
    
    // CAPTCHA reload route
    Route::get('/captcha/reload', function() {
        try {
            // Generate new CAPTCHA and return the image HTML
            $captcha = captcha_img('flat');
            return response()->json(['captcha' => $captcha]);
        } catch (\Exception $e) {
            \Log::error('CAPTCHA reload error: ' . $e->getMessage());
            // Don't expose file paths or line numbers in production
            $errorMessage = config('app.debug') 
                ? $e->getMessage() 
                : 'Failed to generate CAPTCHA. Please try again.';
            
            return response()->json([
                'error' => 'Failed to generate CAPTCHA',
                'message' => $errorMessage
            ], 500);
        }
    })->middleware('throttle:10,1')->name('captcha.reload'); // Rate limit CAPTCHA requests
});

// Protected routes (require authentication)
// Admin routes (main-site - restricted to port 9000)
Route::middleware(['auth:admin', 'restrict.port:9000', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Subdomains
        Route::resource('subdomains', SubdomainController::class);
        Route::post('/subdomains/{subdomain}/toggle-status', [SubdomainController::class, 'toggleStatus'])->name('subdomains.toggle-status');
        Route::post('/subdomains/{subdomain}/generate-link', [SubdomainController::class, 'generateRegistrationLink'])->name('subdomains.generate-link');
        
        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::post('/subscriptions/{subscription}/update-status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');
        Route::post('/subscriptions/{subscription}/send-reminder', [SubscriptionController::class, 'sendPaymentReminder'])->name('subscriptions.send-reminder');
        
        // Reports & Insights
        Route::get('/reports', [ReportsController::class, 'reports'])->name('reports.index');
        Route::get('/insights', [ReportsController::class, 'insights'])->name('insights.index');
});

// Doctor routes (accessible on port 8000 or subdomain-specific ports 10000+)
Route::middleware(['auth:web', 'allow.subdomain.ports', 'subdomain.check'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Patients
        Route::resource('patients', PatientController::class);
        Route::get('/patients/{patient}/teeth-chart', [TeethRecordController::class, 'showChart'])->name('patients.teeth-chart');
        Route::post('/patients/{patient}/teeth-records', [TeethRecordController::class, 'store'])->name('patients.teeth-records.store');
        Route::get('/patients/{patient}/teeth-records/{toothNumber}', [TeethRecordController::class, 'getRecord'])->name('patients.teeth-records.get');
        
        // Appointments
        Route::resource('appointments', AppointmentController::class);
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
});

// Doctor only routes (accessible on port 8000 or subdomain-specific ports 10000+)
Route::middleware(['auth:web', 'allow.subdomain.ports', 'subdomain.check', 'role:doctor'])->group(function () {
        // Availability management - specific routes must come BEFORE resource route
        Route::get('/availability/date-availability', [AvailabilityController::class, 'getDateAvailability'])->name('availability.date-availability');
        Route::post('/availability/quick-set', [AvailabilityController::class, 'quickSetAvailability'])->name('availability.quick-set');
        // Resource route (exclude 'show' since we don't need individual availability view)
        Route::resource('availability', AvailabilityController::class)->except(['show']);
});
