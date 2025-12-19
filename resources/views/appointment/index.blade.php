@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check"></i> Appointments</h2>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Appointment
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                <small class="text-muted">{{ $appointment->appointment_date->format('g:i A') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('patients.show', $appointment->patient) }}">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </a>
                            </td>
                            <td>
                                {{ $appointment->doctor ? $appointment->doctor->name : 'Unassigned' }}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($appointment->type) }}</span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'scheduled' => 'primary',
                                        'confirmed' => 'success',
                                        'in_progress' => 'warning',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        'no_show' => 'secondary'
                                    ];
                                    $color = $statusColors[$appointment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                            </td>
                            <td>{{ $appointment->duration ?? 30 }} min</td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection

