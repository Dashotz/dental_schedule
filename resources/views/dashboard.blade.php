@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
        <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Patients</h6>
                        <h3 class="mb-0">{{ $totalPatients }}</h3>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Today's Appointments</h6>
                        <h3 class="mb-0">{{ $todayAppointmentsCount }}</h3>
                    </div>
                    <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Pending Appointments</h6>
                        <h3 class="mb-0">{{ $pendingAppointments }}</h3>
                    </div>
                    <i class="bi bi-clock-history fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Appointments</h6>
                        <h3 class="mb-0">{{ $totalAppointments }}</h3>
                    </div>
                    <i class="bi bi-calendar3 fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Appointments -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Today's Appointments</h5>
            </div>
            <div class="card-body">
                @if($todayAppointments->count() > 0)
                    <div class="list-group">
                        @foreach($todayAppointments as $appointment)
                            <a href="{{ route('appointments.show', $appointment) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                    <small class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'primary') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </small>
                                </div>
                                <p class="mb-1">
                                    <i class="bi bi-clock"></i> {{ $appointment->appointment_date->format('g:i A') }}
                                    <span class="text-muted">• {{ ucfirst($appointment->type) }}</span>
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No appointments scheduled for today.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                @if($upcomingAppointments->count() > 0)
                    <div class="list-group">
                        @foreach($upcomingAppointments as $appointment)
                            <a href="{{ route('appointments.show', $appointment) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                    <small class="text-muted">{{ $appointment->appointment_date->format('M d') }}</small>
                                </div>
                                <p class="mb-1">
                                    <i class="bi bi-clock"></i> {{ $appointment->appointment_date->format('g:i A') }}
                                    <span class="text-muted">• {{ ucfirst($appointment->type) }}</span>
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No upcoming appointments.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Patients -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Recent Patients</h5>
            </div>
            <div class="card-body">
                @if($recentPatients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPatients as $patient)
                                    <tr>
                                        <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                        <td>{{ $patient->phone }}</td>
                                        <td>{{ $patient->email ?? 'N/A' }}</td>
                                        <td>{{ $patient->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No patients registered yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

