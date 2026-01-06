@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><x-dental-icon name="person" class="w-5 h-5" /> Patient Details</h2>
    <div>
        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning">
            <x-dental-icon name="pencil" class="w-5 h-5" /> Edit
        </a>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">
            <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
        </a>
    </div>
</div>

<div class="row">
    <!-- Patient Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><x-dental-icon name="person-circle" class="w-5 h-5" /> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>First Name:</strong>
                        <p>{{ $patient->first_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Last Name:</strong>
                        <p>{{ $patient->last_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p>{{ $patient->date_of_birth ? $patient->date_of_birth->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p>{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p>{{ $patient->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p>{{ $patient->phone ?? 'N/A' }}</p>
                    </div>
                    @if($patient->phone_alt)
                    <div class="col-md-6 mb-3">
                        <strong>Alternate Phone:</strong>
                        <p>{{ $patient->phone_alt }}</p>
                    </div>
                    @endif
                    <div class="col-12 mb-3">
                        <strong>Address:</strong>
                        <p>{{ $patient->address ?? 'N/A' }}</p>
                    </div>
                    @if($patient->city || $patient->state || $patient->zip_code)
                    <div class="col-md-4 mb-3">
                        <strong>City:</strong>
                        <p>{{ $patient->city ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>State:</strong>
                        <p>{{ $patient->state ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Zip Code:</strong>
                        <p>{{ $patient->zip_code ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><x-dental-icon name="heart-pulse" class="w-5 h-5" /> Medical Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Medical History:</strong>
                        <p>{{ $patient->medical_history ? nl2br(e($patient->medical_history)) : 'None recorded' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Allergies:</strong>
                        <p>{{ $patient->allergies ? nl2br(e($patient->allergies)) : 'None recorded' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Current Medications:</strong>
                        <p>{{ $patient->medications ? nl2br(e($patient->medications)) : 'None recorded' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
        <div class="card mt-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><x-dental-icon name="telephone" class="w-5 h-5" /> Emergency Contact</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p>{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p>{{ $patient->emergency_contact_phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Insurance Information -->
        @if($patient->insurance_provider || $patient->insurance_policy_number)
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><x-dental-icon name="shield-check" class="w-5 h-5" /> Insurance Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Provider:</strong>
                        <p>{{ $patient->insurance_provider ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Policy Number:</strong>
                        <p>{{ $patient->insurance_policy_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Dental Chart -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><x-dental-icon name="tooth" class="w-5 h-5" /> Dental Chart</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('patients.teeth-chart', $patient) }}" class="btn btn-info w-100">
                    <x-dental-icon name="grid" class="w-5 h-5" /> View Teeth Chart
                </a>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><x-dental-icon name="calendar-check" class="w-5 h-5" /> Recent Appointments</h6>
            </div>
            <div class="card-body">
                @if($patient->appointments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($patient->appointments->take(5) as $appointment)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $appointment->appointment_date->format('M d, Y') }}</h6>
                                        <small class="text-muted">{{ $appointment->appointment_date->format('g:i A') }}</small>
                                        <br>
                                        <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'primary') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                        <x-dental-icon name="eye" class="w-5 h-5" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary w-100">
                            View All Appointments
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-0">No appointments yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

