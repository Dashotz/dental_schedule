@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><x-dental-icon name="pencil" class="w-5 h-5" /> Edit Appointment</h2>
    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary">
        <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
    </a>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Edit Appointment Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">Select Patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id">
                                <option value="">Unassigned</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-4">
                            <label for="appointment_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                   id="appointment_date" name="appointment_date" 
                                   value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="appointment_time" class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                   id="appointment_time" name="appointment_time" 
                                   value="{{ old('appointment_time', $appointment->appointment_date->format('H:i')) }}" required>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="duration" class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" name="duration" 
                                   value="{{ old('duration', $appointment->duration ?? 30) }}" min="15" step="15">
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="consultation" {{ old('type', $appointment->type) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="cleaning" {{ old('type', $appointment->type) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                <option value="procedure" {{ old('type', $appointment->type) == 'procedure' ? 'selected' : '' }}>Procedure</option>
                                <option value="follow_up" {{ old('type', $appointment->type) == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                <option value="emergency" {{ old('type', $appointment->type) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="other" {{ old('type', $appointment->type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ old('status', $appointment->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="reason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3">{{ old('reason', $appointment->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <x-dental-icon name="check-circle" class="w-5 h-5" /> Update Appointment
                        </button>
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary">
                            <x-dental-icon name="x-circle" class="w-5 h-5" /> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

