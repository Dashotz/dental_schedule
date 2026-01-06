@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="calendar-check" class="w-5 h-5" /> Appointments
    </h2>
    <a href="{{ route('appointments.create') }}" class="btn-dental">
        <x-dental-icon name="plus-circle" class="w-5 h-5" /> New Appointment
    </a>
</div>

<div class="card-dental">
    <div class="card-dental-header">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="calendar-check" class="w-5 h-5" /> All Appointments
        </h5>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <strong class="text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                <small class="text-gray-500">{{ $appointment->appointment_date->format('g:i A') }}</small>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button type="button" class="text-dental-teal hover:text-dental-teal-dark font-medium view-patient-btn" data-patient-id="{{ $appointment->patient->id }}">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </button>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $appointment->doctor ? $appointment->doctor->name : 'Unassigned' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-cyan-100 text-cyan-800">{{ ucfirst($appointment->type) }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
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
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $appointment->duration ?? 30 }} min
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn-dental text-sm py-1.5 px-3">
                                    <x-dental-icon name="eye" class="w-5 h-5" />
                                </a>
                                <a href="{{ route('appointments.edit', $appointment) }}" class="btn-dental-outline text-sm py-1.5 px-3 border-yellow-500 text-yellow-600 hover:bg-yellow-50">
                                    <x-dental-icon name="pencil" class="w-5 h-5" />
                                </a>
                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-dental-outline text-sm py-1.5 px-3 border-red-500 text-red-600 hover:bg-red-50">
                                        <x-dental-icon name="trash" class="w-5 h-5" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $appointments->links() }}
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
