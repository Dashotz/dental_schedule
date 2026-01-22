<div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="viewPatientModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
        <div class="card-dental-header flex justify-between items-center">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="person-circle" class="w-5 h-5" /> {{ $patient->first_name }} {{ $patient->last_name }}
            </h5>
            <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('viewPatientModal')">
                &times;
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <x-dental-icon name="person" class="w-4 h-4 text-dental-teal" /> Personal Information
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">First Name</label>
                            <strong class="block text-gray-900">{{ $patient->first_name }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Last Name</label>
                            <strong class="block text-gray-900">{{ $patient->last_name }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Date of Birth</label>
                            <strong class="block text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Gender</label>
                            <strong class="block text-gray-900">{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Email</label>
                            <strong class="block text-gray-900">{{ $patient->email ?? 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Phone</label>
                            <strong class="block text-gray-900">{{ $patient->phone ?? 'N/A' }}</strong>
                        </div>
                        @if($patient->phone_alt)
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Alternate Phone</label>
                            <strong class="block text-gray-900">{{ $patient->phone_alt }}</strong>
                        </div>
                        @endif
                        <div class="bg-gray-50 p-3 rounded-lg md:col-span-3">
                            <label class="text-gray-500 text-xs mb-1 block">Address</label>
                            <strong class="block text-gray-900">{{ $patient->address ?? 'N/A' }}</strong>
                        </div>
                        @if($patient->city || $patient->state || $patient->zip_code)
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">City</label>
                            <strong class="block text-gray-900">{{ $patient->city ?? 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">State</label>
                            <strong class="block text-gray-900">{{ $patient->state ?? 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Zip Code</label>
                            <strong class="block text-gray-900">{{ $patient->zip_code ?? 'N/A' }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Medical Information -->
                <div>
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <x-dental-icon name="heart-pulse" class="w-4 h-4 text-dental-teal" /> Medical Information
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Medical History</label>
                            <p class="text-gray-900 text-sm whitespace-pre-line">{{ $patient->medical_history ?: 'None recorded' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Allergies</label>
                            <p class="text-gray-900 text-sm whitespace-pre-line">{{ $patient->allergies ?: 'None recorded' }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Current Medications</label>
                            <p class="text-gray-900 text-sm whitespace-pre-line">{{ $patient->medications ?: 'None recorded' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
                <div>
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <x-dental-icon name="telephone" class="w-4 h-4 text-dental-teal" /> Emergency Contact
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Name</label>
                            <strong class="block text-gray-900">{{ $patient->emergency_contact_name ?? 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Phone</label>
                            <strong class="block text-gray-900">{{ $patient->emergency_contact_phone ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Insurance Information -->
                @if($patient->insurance_provider || $patient->insurance_policy_number)
                <div>
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <x-dental-icon name="shield-check" class="w-4 h-4 text-dental-teal" /> Insurance Information
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Provider</label>
                            <strong class="block text-gray-900">{{ $patient->insurance_provider ?? 'N/A' }}</strong>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="text-gray-500 text-xs mb-1 block">Policy Number</label>
                            <strong class="block text-gray-900">{{ $patient->insurance_policy_number ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recent Appointments -->
                @if($patient->appointments->count() > 0)
                <div>
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <x-dental-icon name="calendar-check" class="w-4 h-4 text-dental-teal" /> Recent Appointments
                    </h6>
                    <div class="space-y-2">
                        @foreach($patient->appointments->take(5) as $appointment)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <strong class="text-gray-900 block">{{ $appointment->appointment_date->format('M d, Y g:i A') }}</strong>
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'default' => 'bg-blue-100 text-blue-800'
                                            ];
                                            $color = $statusColors[$appointment->status] ?? $statusColors['default'];
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $color }} mt-1 inline-block">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" class="btn-dental-outline border-yellow-500 text-yellow-600 hover:bg-yellow-50 edit-patient-btn" data-patient-id="{{ $patient->id }}">
                <x-dental-icon name="pencil" class="w-4 h-4" /> Edit
            </button>
            <a href="{{ route('patients.teeth-chart', $patient) }}" class="btn-dental">
                <x-dental-icon name="grid" class="w-4 h-4" /> View Teeth Chart
            </a>
            <button onclick="closeModal('viewPatientModal')" class="btn-dental-outline">Close</button>
        </div>
    </div>
</div>

