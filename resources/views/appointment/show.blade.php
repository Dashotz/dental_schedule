@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><x-dental-icon name="calendar-check" class="w-5 h-5" /> Appointment Details</h2>
    <div>
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
            <x-dental-icon name="pencil" class="w-5 h-5" /> Edit
        </a>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
            <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Appointment Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Date & Time:</th>
                        <td>
                            <strong>{{ $appointment->appointment_date->format('F d, Y') }}</strong><br>
                            <small class="text-muted">{{ $appointment->appointment_date->format('g:i A') }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Patient:</th>
                        <td>
                            <a href="{{ route('patients.show', $appointment->patient) }}">
                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $appointment->patient->phone ?? 'N/A' }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Doctor:</th>
                        <td>{{ $appointment->doctor ? $appointment->doctor->name : 'Unassigned' }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><span class="badge bg-info">{{ ucfirst($appointment->type) }}</span></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
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
                    </tr>
                    <tr>
                        <th>Duration:</th>
                        <td>{{ $appointment->duration ?? 30 }} minutes</td>
                    </tr>
                    @if($appointment->reason)
                    <tr>
                        <th>Reason:</th>
                        <td>{{ $appointment->reason }}</td>
                    </tr>
                    @endif
                    @if($appointment->notes)
                    <tr>
                        <th>Notes:</th>
                        <td>{{ $appointment->notes }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Created By:</th>
                        <td>{{ $appointment->createdBy ? $appointment->createdBy->name : 'System' }}</td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $appointment->created_at->format('F d, Y g:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('patients.show', $appointment->patient) }}" class="btn btn-primary w-100 mb-2">
                    <x-dental-icon name="person" class="w-5 h-5" /> View Patient
                </a>
                @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <x-dental-icon name="x-circle" class="w-5 h-5" /> Cancel Appointment
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

