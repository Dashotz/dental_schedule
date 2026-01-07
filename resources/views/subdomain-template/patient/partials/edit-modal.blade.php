<div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="editPatientModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="person-gear" class="w-5 h-5" /> Edit Patient Information
            </h5>
            <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('editPatientModal')">
                &times;
            </button>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('patients.update', $patient) }}" id="editPatientForm">
                @csrf
                @method('PUT')

                <!-- Personal Information -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <x-dental-icon name="person" class="w-4 h-4 text-dental-teal" /> Personal Information
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="first_name" class="form-label">First Name <span class="text-red-500">*</span></label>
                            <input type="text" class="input-dental @error('first_name') border-red-500 @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="form-label">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" class="input-dental @error('last_name') border-red-500 @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="input-dental @error('date_of_birth') border-red-500 @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="form-label">Gender</label>
                            <select class="input-dental @error('gender') border-red-500 @enderror" id="gender" name="gender">
                                <option value="">Select...</option>
                                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="input-dental @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email', $patient->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="form-label">Phone <span class="text-red-500">*</span></label>
                            <input type="text" class="input-dental @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_alt" class="form-label">Alternate Phone</label>
                            <input type="text" class="input-dental @error('phone_alt') border-red-500 @enderror" 
                                   id="phone_alt" name="phone_alt" value="{{ old('phone_alt', $patient->phone_alt) }}">
                            @error('phone_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="md:col-span-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="input-dental @error('address') border-red-500 @enderror" 
                                   id="address" name="address" value="{{ old('address', $patient->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="input-dental @error('city') border-red-500 @enderror" 
                                   id="city" name="city" value="{{ old('city', $patient->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="input-dental @error('state') border-red-500 @enderror" 
                                   id="state" name="state" value="{{ old('state', $patient->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="input-dental @error('zip_code') border-red-500 @enderror" 
                                   id="zip_code" name="zip_code" value="{{ old('zip_code', $patient->zip_code) }}">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <x-dental-icon name="heart-pulse" class="w-4 h-4 text-dental-teal" /> Medical Information
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="medical_history" class="form-label">Medical History</label>
                            <textarea class="input-dental @error('medical_history') border-red-500 @enderror" 
                                      id="medical_history" name="medical_history" rows="4">{{ old('medical_history', $patient->medical_history) }}</textarea>
                            @error('medical_history')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="allergies" class="form-label">Allergies</label>
                            <textarea class="input-dental @error('allergies') border-red-500 @enderror" 
                                      id="allergies" name="allergies" rows="4">{{ old('allergies', $patient->allergies) }}</textarea>
                            @error('allergies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="medications" class="form-label">Current Medications</label>
                            <textarea class="input-dental @error('medications') border-red-500 @enderror" 
                                      id="medications" name="medications" rows="4">{{ old('medications', $patient->medications) }}</textarea>
                            @error('medications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <x-dental-icon name="telephone" class="w-4 h-4 text-dental-teal" /> Emergency Contact
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                            <input type="text" class="input-dental @error('emergency_contact_name') border-red-500 @enderror" 
                                   id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                            <input type="text" class="input-dental @error('emergency_contact_phone') border-red-500 @enderror" 
                                   id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <x-dental-icon name="shield-check" class="w-4 h-4 text-dental-teal" /> Insurance Information
                    </h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="insurance_provider" class="form-label">Insurance Provider</label>
                            <input type="text" class="input-dental @error('insurance_provider') border-red-500 @enderror" 
                                   id="insurance_provider" name="insurance_provider" value="{{ old('insurance_provider', $patient->insurance_provider) }}">
                            @error('insurance_provider')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="insurance_policy_number" class="form-label">Policy Number</label>
                            <input type="text" class="input-dental @error('insurance_policy_number') border-red-500 @enderror" 
                                   id="insurance_policy_number" name="insurance_policy_number" value="{{ old('insurance_policy_number', $patient->insurance_policy_number) }}">
                            @error('insurance_policy_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="input-dental @error('notes') border-red-500 @enderror" 
                              id="notes" name="notes" rows="3">{{ old('notes', $patient->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeModal('editPatientModal')" class="btn-dental-outline">
                <x-dental-icon name="x-circle" class="w-4 h-4" /> Cancel
            </button>
            <button type="submit" form="editPatientForm" class="btn-dental">
                <x-dental-icon name="check-circle" class="w-4 h-4" /> Update Patient
            </button>
        </div>
    </div>
</div>

