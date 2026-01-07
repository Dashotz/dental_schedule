@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="calendar-check" class="w-8 h-8 text-dental-teal" /> Appointment Details
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn-dental-outline border-yellow-500 text-yellow-600 hover:bg-yellow-50">
            <x-dental-icon name="pencil" class="w-5 h-5" /> Edit
        </a>
        <a href="{{ route('appointments.index') }}" class="btn-dental-outline">
            <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card-dental">
            <div class="card-dental-header">
                <h5 class="text-lg font-semibold flex items-center gap-2">
                    <x-dental-icon name="calendar-check" class="w-5 h-5" /> Appointment Information
                </h5>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50 w-48">Date & Time:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <strong>{{ $appointment->appointment_date->format('F d, Y') }}</strong><br>
                                    <small class="text-gray-500">{{ $appointment->appointment_date->format('g:i A') }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Patient:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <button type="button" class="text-dental-teal hover:text-dental-teal-dark font-medium view-patient-btn" data-patient-id="{{ $appointment->patient->id }}">
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    </button>
                                    <br>
                                    <small class="text-gray-500">{{ $appointment->patient->phone ?? 'N/A' }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Doctor:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->doctor ? $appointment->doctor->name : 'Unassigned' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Type:</th>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-cyan-100 text-cyan-800">{{ ucfirst($appointment->type) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Status:</th>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-blue-100 text-blue-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'no_show' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $color = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Duration:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->duration ?? 30 }} minutes</td>
                            </tr>
                            @if($appointment->reason)
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Reason:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->reason }}</td>
                            </tr>
                            @endif
                            @if($appointment->notes)
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Notes:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->notes }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Created By:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->createdBy ? $appointment->createdBy->name : 'System' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50">Created At:</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $appointment->created_at->format('F d, Y g:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="card-dental">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-2xl">
                <h6 class="text-base font-semibold flex items-center gap-2">
                    <x-dental-icon name="lightning" class="w-5 h-5" /> Quick Actions
                </h6>
            </div>
            <div class="p-6 space-y-3">
                <button type="button" class="btn-dental w-full text-center view-patient-btn" data-patient-id="{{ $appointment->patient->id }}">
                    <x-dental-icon name="person" class="w-5 h-5" /> View Patient
                </button>
                @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-dental-outline w-full text-center border-red-500 text-red-600 hover:bg-red-50">
                            <x-dental-icon name="x-circle" class="w-5 h-5" /> Cancel Appointment
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Patient View Modal Container -->
<div id="viewPatientModalContainer"></div>

@push('scripts')
<script>
    $(document).ready(function() {
        // View patient modal (same as in patient index)
        $(document).on('click', '.view-patient-btn', function(e) {
            e.preventDefault();
            const patientId = $(this).data('patient-id');
            
            // Check if container exists
            if ($('#viewPatientModalContainer').length === 0) {
                $('body').append('<div id="viewPatientModalContainer"></div>');
            }
            
            // Show loading state
            $('#viewPatientModalContainer').html(`
                <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="viewPatientModal">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
                        <div class="card-dental-header flex justify-between items-center">
                            <h5 class="text-lg font-semibold">Loading...</h5>
                            <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('viewPatientModal')">
                                &times;
                            </button>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-dental-teal"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            // Open modal
            openModal('viewPatientModal');
            
            // Load patient data
            $.ajax({
                url: `/patients/${patientId}`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#viewPatientModalContainer').html(response.html);
                    const modal = document.getElementById('viewPatientModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                },
                error: function(xhr) {
                    $('#viewPatientModalContainer').html(`
                        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="viewPatientModal">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
                                <div class="card-dental-header flex justify-between items-center">
                                    <h5 class="text-lg font-semibold">Error</h5>
                                    <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('viewPatientModal')">
                                        &times;
                                    </button>
                                </div>
                                <div class="p-6">
                                    <p class="text-red-600">Failed to load patient details. Please try again.</p>
                                </div>
                                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                                    <button onclick="closeModal('viewPatientModal')" class="btn-dental-outline">Close</button>
                                </div>
                            </div>
                        </div>
                    `);
                    openModal('viewPatientModal');
                }
            });
        });
    });
</script>
@endpush
@endsection
