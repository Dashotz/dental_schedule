@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="people" class="w-5 h-5" /> Patients
    </h2>
    <form method="GET" action="{{ route('patients.index') }}" class="flex gap-2">
        <input type="text" name="search" class="input-dental" placeholder="Search patients..." value="{{ request('search') }}">
        <button class="btn-dental" type="submit">
            <x-dental-icon name="search" class="w-5 h-5" />
        </button>
    </form>
</div>

<div class="card-dental">
    <div class="card-dental-header">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="people" class="w-5 h-5" /> All Patients
        </h5>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <strong class="text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $patient->email ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $patient->phone ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $patient->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <button type="button" class="btn-dental text-sm py-1.5 px-3 inline-flex items-center gap-1 view-patient-btn" data-patient-id="{{ $patient->id }}">
                                    <x-dental-icon name="eye" class="w-4 h-4" /> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No patients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </div>
</div>

<!-- Patient View Modal Container -->
<div id="viewPatientModalContainer"></div>

<!-- Patient Edit Modal Container -->
<div id="editPatientModalContainer"></div>

@push('scripts')
<script src="{{ asset('js/patient-modals.js') }}" defer></script>
@endpush
@endsection
