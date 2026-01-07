<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Patient::class => \App\Policies\PatientPolicy::class,
        \App\Models\Appointment::class => \App\Policies\AppointmentPolicy::class,
        \App\Models\DoctorAvailability::class => \App\Policies\AvailabilityPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
