<?php

use App\Http\Controllers\Auth\LoginController;
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
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Patient registration via special link only
Route::get('/register/{token}', [RegistrationLinkController::class, 'showRegistrationForm'])->name('patient.register');
Route::post('/register/{token}', [PatientController::class, 'store'])->name('patient.store');

// CAPTCHA reload route
Route::get('/captcha/reload', function() {
    try {
        // Generate new CAPTCHA and return the image HTML
        $captcha = captcha_img('flat');
        return response()->json(['captcha' => $captcha]);
    } catch (\Exception $e) {
        \Log::error('CAPTCHA reload error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
        // Return error details for debugging
        return response()->json([
            'error' => 'Failed to generate CAPTCHA',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('captcha.reload');

// Authentication routes (Doctor/Staff only)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Subdomains
        Route::resource('subdomains', SubdomainController::class);
        Route::post('/subdomains/{subdomain}/toggle-status', [SubdomainController::class, 'toggleStatus'])->name('subdomains.toggle-status');
        Route::post('/subdomains/{subdomain}/generate-link', [SubdomainController::class, 'generateRegistrationLink'])->name('subdomains.generate-link');
        
        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subdomains/{subdomain}/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subdomains/{subdomain}/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::post('/subscriptions/{subscription}/update-status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');
        Route::post('/subscriptions/{subscription}/send-reminder', [SubscriptionController::class, 'sendPaymentReminder'])->name('subscriptions.send-reminder');
        
        // Reports & Insights
        Route::get('/reports', [ReportsController::class, 'reports'])->name('reports.index');
        Route::get('/insights', [ReportsController::class, 'insights'])->name('insights.index');
    });
    
    // Doctor/Staff Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Patients
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/teeth-chart', [TeethRecordController::class, 'showChart'])->name('patients.teeth-chart');
    Route::post('/patients/{patient}/teeth-records', [TeethRecordController::class, 'store'])->name('patients.teeth-records.store');
    Route::get('/patients/{patient}/teeth-records/{toothNumber}', [TeethRecordController::class, 'getRecord'])->name('patients.teeth-records.get');
    
    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    
    // Doctor only routes
    Route::middleware('role:doctor')->group(function () {
        // Add doctor-specific routes here
    });
    
    // Staff only routes (if needed)
    Route::middleware('role:staff')->group(function () {
        // Add staff-specific routes here
    });
});
