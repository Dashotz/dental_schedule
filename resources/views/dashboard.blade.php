@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="speedometer2" class="w-8 h-8 text-dental-teal" /> Dashboard
    </h2>
    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-dental bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Total Patients</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $totalPatients }}</h3>
                </div>
                <x-dental-icon name="people" class="w-12 h-12 opacity-50" />
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Today's Appointments</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $todayAppointmentsCount }}</h3>
                </div>
                <x-dental-icon name="calendar-check" class="w-12 h-12 opacity-50" />
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-cyan-500 to-cyan-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Pending Appointments</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $pendingAppointments }}</h3>
                </div>
                <x-dental-icon name="clock-history" class="w-12 h-12 opacity-50" />
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Total Appointments</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $totalAppointments }}</h3>
                </div>
                <x-dental-icon name="calendar3" class="w-12 h-12 opacity-50" />
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Today's Appointments -->
    <div class="card-dental">
        <div class="card-dental-header">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="calendar-check" class="w-5 h-5" /> Today's Appointments
            </h5>
        </div>
        <div class="p-6">
            @if($todayAppointments->count() > 0)
                <div class="space-y-2">
                    @foreach($todayAppointments as $appointment)
                        <a href="{{ route('appointments.show', $appointment) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-dental-teal transition-colors">
                            <div class="flex w-full justify-between items-start mb-2">
                                <h6 class="font-semibold text-gray-800">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <x-dental-icon name="clock" class="w-4 h-4" /> {{ $appointment->appointment_date->format('g:i A') }}
                                <span class="text-gray-500">• {{ ucfirst($appointment->type) }}</span>
                            </p>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No appointments scheduled for today.</p>
            @endif
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="calendar" class="w-5 h-5" /> Upcoming Appointments
            </h5>
        </div>
        <div class="p-6">
            @if($upcomingAppointments->count() > 0)
                <div class="space-y-2">
                    @foreach($upcomingAppointments as $appointment)
                        <a href="{{ route('appointments.show', $appointment) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-dental-teal transition-colors">
                            <div class="flex w-full justify-between items-start mb-2">
                                <h6 class="font-semibold text-gray-800">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</h6>
                                <small class="text-gray-500">{{ $appointment->appointment_date->format('M d') }}</small>
                            </div>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <x-dental-icon name="clock" class="w-4 h-4" /> {{ $appointment->appointment_date->format('g:i A') }}
                                <span class="text-gray-500">• {{ ucfirst($appointment->type) }}</span>
                            </p>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No upcoming appointments.</p>
            @endif
        </div>
    </div>
</div>

<!-- Recent Patients -->
<div class="card-dental">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="people" class="w-5 h-5" /> Recent Patients
        </h5>
    </div>
    <div class="p-6">
        @if($recentPatients->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentPatients as $patient)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->phone }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->email ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $patient->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('patients.show', $patient) }}" class="btn-dental text-sm py-1.5 px-3">
                                        <x-dental-icon name="eye" class="w-4 h-4 mr-1" /> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No patients registered yet.</p>
        @endif
    </div>
</div>
@endsection
