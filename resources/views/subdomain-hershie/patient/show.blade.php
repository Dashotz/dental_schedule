@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="person" class="w-8 h-8 text-dental-teal" /> Patient Details
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('patients.edit', $patient) }}" class="btn-dental-outline border-yellow-500 text-yellow-600 hover:bg-yellow-50">
            <x-dental-icon name="pencil" class="w-5 h-5" /> Edit
        </a>
        <a href="{{ route('patients.index') }}" class="btn-dental-outline">
            <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Patient Information -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card-dental">
            <div class="card-dental-header">
                <h5 class="text-lg font-semibold flex items-center gap-2">
                    <x-dental-icon name="person-circle" class="w-5 h-5" /> Personal Information
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">First Name:</strong>
                        <p class="text-gray-900">{{ $patient->first_name }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Last Name:</strong>
                        <p class="text-gray-900">{{ $patient->last_name }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Date of Birth:</strong>
                        <p class="text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Gender:</strong>
                        <p class="text-gray-900">{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Email:</strong>
                        <p class="text-gray-900">{{ $patient->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Phone:</strong>
                        <p class="text-gray-900">{{ $patient->phone ?? 'N/A' }}</p>
                    </div>
                    @if($patient->phone_alt)
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Alternate Phone:</strong>
                        <p class="text-gray-900">{{ $patient->phone_alt }}</p>
                    </div>
                    @endif
                    <div class="md:col-span-2">
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Address:</strong>
                        <p class="text-gray-900">{{ $patient->address ?? 'N/A' }}</p>
                    </div>
                    @if($patient->city || $patient->state || $patient->zip_code)
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">City:</strong>
                        <p class="text-gray-900">{{ $patient->city ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">State:</strong>
                        <p class="text-gray-900">{{ $patient->state ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Zip Code:</strong>
                        <p class="text-gray-900">{{ $patient->zip_code ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="card-dental">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-2xl">
                <h5 class="text-lg font-semibold flex items-center gap-2">
                    <x-dental-icon name="heart-pulse" class="w-5 h-5" /> Medical Information
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Medical History:</strong>
                        <p class="text-gray-900 whitespace-pre-line">{{ $patient->medical_history ?: 'None recorded' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Allergies:</strong>
                        <p class="text-gray-900 whitespace-pre-line">{{ $patient->allergies ?: 'None recorded' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Current Medications:</strong>
                        <p class="text-gray-900 whitespace-pre-line">{{ $patient->medications ?: 'None recorded' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
        <div class="card-dental">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-2xl">
                <h5 class="text-lg font-semibold flex items-center gap-2">
                    <x-dental-icon name="telephone" class="w-5 h-5" /> Emergency Contact
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Name:</strong>
                        <p class="text-gray-900">{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Phone:</strong>
                        <p class="text-gray-900">{{ $patient->emergency_contact_phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Insurance Information -->
        @if($patient->insurance_provider || $patient->insurance_policy_number)
        <div class="card-dental">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-2xl">
                <h5 class="text-lg font-semibold flex items-center gap-2">
                    <x-dental-icon name="shield-check" class="w-5 h-5" /> Insurance Information
                </h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Provider:</strong>
                        <p class="text-gray-900">{{ $patient->insurance_provider ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-sm font-medium text-gray-700 block mb-1">Policy Number:</strong>
                        <p class="text-gray-900">{{ $patient->insurance_policy_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Dental Chart -->
        <div class="card-dental">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-2xl">
                <h6 class="text-base font-semibold flex items-center gap-2">
                    <x-dental-icon name="tooth" class="w-5 h-5" /> Dental Chart
                </h6>
            </div>
            <div class="p-6">
                <a href="{{ route('patients.teeth-chart', $patient) }}" class="btn-dental w-full text-center">
                    <x-dental-icon name="grid" class="w-5 h-5" /> View Teeth Chart
                </a>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="card-dental">
            <div class="card-dental-header">
                <h6 class="text-base font-semibold flex items-center gap-2">
                    <x-dental-icon name="calendar-check" class="w-5 h-5" /> Recent Appointments
                </h6>
            </div>
            <div class="p-6">
                @if($patient->appointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($patient->appointments->take(5) as $appointment)
                            <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h6 class="font-semibold text-gray-800 mb-1">{{ $appointment->appointment_date->format('M d, Y') }}</h6>
                                        <small class="text-gray-500 block mb-2">{{ $appointment->appointment_date->format('g:i A') }}</small>
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'default' => 'bg-blue-100 text-blue-800'
                                            ];
                                            $color = $statusColors[$appointment->status] ?? $statusColors['default'];
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn-dental-outline text-sm py-1.5 px-3">
                                        <x-dental-icon name="eye" class="w-4 h-4" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('appointments.index') }}" class="btn-dental-outline w-full text-center text-sm">
                            View All Appointments
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No appointments yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
