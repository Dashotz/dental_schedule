@extends('layouts.app')

@section('title', 'Patient Registration & Appointment Booking')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="registration-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="card registration-card">
                    <div class="card-header registration-header">
                        <h4>
                            <i class="bi bi-clipboard-pulse"></i> Patient Registration & Appointment Booking
                        </h4>
                        <i class="bi bi-tooth dental-decoration top-right"></i>
                    </div>
                    <div class="card-body registration-body">
                <form method="POST" action="{{ route('patient.store', $registrationLink->token ?? '') }}" id="registrationForm">
                    @csrf

                        <!-- Personal Information -->
                        <h5 class="section-title"><i class="bi bi-person"></i> Personal Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Select...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <h5 class="section-title mt-4"><i class="bi bi-telephone"></i> Contact Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="phone_alt" class="form-label">Alternate Phone</label>
                                <input type="tel" class="form-control @error('phone_alt') is-invalid @enderror" 
                                       id="phone_alt" name="phone_alt" value="{{ old('phone_alt') }}">
                                @error('phone_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="zip_code" class="form-label">Zip Code</label>
                                <input type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                                       id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                                @error('zip_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <h5 class="section-title mt-4"><i class="bi bi-person-exclamation"></i> Emergency Contact</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                                <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <h5 class="section-title mt-4"><i class="bi bi-heart-pulse"></i> Medical Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label for="medical_history" class="form-label">Medical History</label>
                                <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                                          id="medical_history" name="medical_history" rows="2">{{ old('medical_history') }}</textarea>
                                @error('medical_history')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="allergies" class="form-label">Allergies</label>
                                <textarea class="form-control @error('allergies') is-invalid @enderror" 
                                          id="allergies" name="allergies" rows="2">{{ old('allergies') }}</textarea>
                                @error('allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="medications" class="form-label">Current Medications</label>
                                <textarea class="form-control @error('medications') is-invalid @enderror" 
                                          id="medications" name="medications" rows="2">{{ old('medications') }}</textarea>
                                @error('medications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Insurance Information -->
                        <h5 class="section-title mt-4"><i class="bi bi-shield-check"></i> Insurance Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="insurance_provider" class="form-label">Insurance Provider</label>
                                <input type="text" class="form-control @error('insurance_provider') is-invalid @enderror" 
                                       id="insurance_provider" name="insurance_provider" value="{{ old('insurance_provider') }}">
                                @error('insurance_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="insurance_policy_number" class="form-label">Policy Number</label>
                                <input type="text" class="form-control @error('insurance_policy_number') is-invalid @enderror" 
                                       id="insurance_policy_number" name="insurance_policy_number" value="{{ old('insurance_policy_number') }}">
                                @error('insurance_policy_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Appointment Information -->
                        <h5 class="section-title mt-4"><i class="bi bi-calendar-check"></i> Appointment Booking</h5>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                       id="appointment_date" name="appointment_date" 
                                       value="{{ old('appointment_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="dateStatus"></small>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                                <select class="form-select @error('appointment_time') is-invalid @enderror" 
                                        id="appointment_time" name="appointment_time" required disabled>
                                    <option value="">Select date first</option>
                                </select>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="timeStatus"></small>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="appointment_type" class="form-label">Appointment Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('appointment_type') is-invalid @enderror" 
                                        id="appointment_type" name="appointment_type" required>
                                    <option value="">Select...</option>
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="cleaning" {{ old('appointment_type') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                    <option value="procedure" {{ old('appointment_type') == 'procedure' ? 'selected' : '' }}>Procedure</option>
                                    <option value="follow_up" {{ old('appointment_type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                    <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    <option value="other" {{ old('appointment_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('appointment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="reason" class="form-label">Reason for Visit</label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" 
                                          id="reason" name="reason" rows="2">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CAPTCHA -->
                        <div class="row mt-4">
                            <div class="col-12 col-md-6 offset-md-3">
                                <label for="captcha" class="form-label text-center d-block">Security Verification <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <div id="captcha-container">
                                        {!! captcha_img('flat') !!}
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary" id="reload-captcha" title="Reload CAPTCHA">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control mt-2 @error('captcha') is-invalid @enderror" 
                                       id="captcha" name="captcha" placeholder="Enter CAPTCHA code" required>
                                @error('captcha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-submit" id="submitBtn">
                                <i class="bi bi-check-circle"></i> Submit Registration & Book Appointment
                            </button>
                        </div>
                    </form>
                    <i class="bi bi-tooth dental-decoration bottom-left"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/form-validation.js"></script>
<script>
    $(document).ready(function() {
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        $('#appointment_date').attr('min', tomorrow.toISOString().split('T')[0]);

        // Doctor IDs for availability checking
        const doctorIds = @json($doctors->pluck('id')->toArray());

        // Load available time slots when date is selected
        $('#appointment_date').on('change', function() {
            const selectedDate = $(this).val();
            const timeSelect = $('#appointment_time');
            const dateStatus = $('#dateStatus');
            const timeStatus = $('#timeStatus');

            if (!selectedDate) {
                timeSelect.html('<option value="">Select date first</option>').prop('disabled', true);
                dateStatus.text('');
                return;
            }

            // Check if date is blocked for all doctors
            checkDateAvailability(selectedDate, doctorIds, timeSelect, dateStatus, timeStatus);
        });

        function checkDateAvailability(date, doctorIds, timeSelect, dateStatus, timeStatus) {
            dateStatus.html('<i class="bi bi-hourglass-split"></i> Checking availability...');
            timeSelect.html('<option value="">Loading...</option>').prop('disabled', true);

            // Check availability for all doctors
            let allSlots = [];
            let checkedDoctors = 0;
            let hasAvailableDoctor = false;

            if (doctorIds.length === 0) {
                dateStatus.html('<span class="text-danger"><i class="bi bi-x-circle"></i> No doctors available</span>');
                timeSelect.html('<option value="">No doctors available</option>').prop('disabled', true);
                return;
            }

            doctorIds.forEach(doctorId => {
                fetch(`/availability/slots?doctor_id=${doctorId}&date=${date}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    checkedDoctors++;
                    
                    if (data.slots && data.slots.length > 0) {
                        hasAvailableDoctor = true;
                        // Merge slots from all doctors
                        data.slots.forEach(slot => {
                            if (!allSlots.find(s => s.start === slot.start && s.end === slot.end)) {
                                allSlots.push(slot);
                            }
                        });
                    }

                    // When all doctors checked
                    if (checkedDoctors === doctorIds.length) {
                        if (hasAvailableDoctor && allSlots.length > 0) {
                            // Sort slots by time
                            allSlots.sort((a, b) => a.start.localeCompare(b.start));
                            
                            // Populate time select (show all slots, but gray out blocked/unavailable ones)
                            timeSelect.html('<option value="">Select time...</option>');
                            let availableCount = 0;
                            allSlots.forEach(slot => {
                                const startTime = formatTime(slot.start);
                                const endTime = formatTime(slot.end);
                                const isAvailable = slot.is_available !== false; // Default to true if not specified
                                const isBlocked = slot.is_blocked === true;
                                const isBooked = slot.is_booked === true;
                                
                                if (isAvailable && !isBlocked && !isBooked) {
                                    availableCount++;
                                    timeSelect.append(`<option value="${slot.start}">${startTime} - ${endTime}</option>`);
                                } else {
                                    // Show blocked/booked slots but disable them
                                    const reason = isBlocked ? ' (Blocked)' : isBooked ? ' (Booked)' : ' (Unavailable)';
                                    timeSelect.append(`<option value="${slot.start}" disabled style="color: #999; background-color: #f5f5f5;">${startTime} - ${endTime}${reason}</option>`);
                                }
                            });
                            
                            // Update available count
                            if (availableCount > 0) {
                                timeStatus.text(`${availableCount} time slot(s) available`);
                            } else {
                                timeStatus.text('No available slots');
                            }
                            
                            timeSelect.prop('disabled', false);
                            dateStatus.html('<span class="text-success"><i class="bi bi-check-circle"></i> Available</span>');
                            timeStatus.text(`${allSlots.length} time slot(s) available`);
                        } else {
                            dateStatus.html('<span class="text-danger"><i class="bi bi-x-circle"></i> No available time slots for this date</span>');
                            timeSelect.html('<option value="">No available slots</option>').prop('disabled', true);
                            timeStatus.text('Please select another date');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking availability:', error);
                    checkedDoctors++;
                    
                    if (checkedDoctors === doctorIds.length) {
                        if (!hasAvailableDoctor) {
                            dateStatus.html('<span class="text-danger"><i class="bi bi-x-circle"></i> Unable to check availability</span>');
                            timeSelect.html('<option value="">Error loading slots</option>').prop('disabled', true);
                        }
                    }
                });
            });
        }

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        }

        // Reload CAPTCHA
        $('#reload-captcha').on('click', function() {
            const captchaContainer = $('#captcha-container');
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="bi bi-arrow-clockwise spin"></i>');
            
            $.ajax({
                url: '/captcha/reload',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Replace the entire CAPTCHA image
                    if (data.captcha) {
                        captchaContainer.html(data.captcha);
                    } else if (data.url) {
                        const img = captchaContainer.find('img');
                        if (img.length) {
                            img.attr('src', data.url + '&t=' + new Date().getTime());
                        } else {
                            captchaContainer.html('<img src="' + data.url + '&t=' + new Date().getTime() + '" alt="captcha">');
                        }
                    } else {
                        // Fallback: use captcha_src
                        window.location.reload();
                    }
                    btn.prop('disabled', false).html('<i class="bi bi-arrow-clockwise"></i>');
                },
                error: function(xhr, status, error) {
                    console.error('CAPTCHA reload error:', error, xhr.responseText);
                    btn.prop('disabled', false).html('<i class="bi bi-arrow-clockwise"></i>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to reload CAPTCHA. Please refresh the page.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });

        // Form validation with guard clauses
        $('#registrationForm').on('submit', function(e) {
            if (!FormValidator.validateRegistrationForm()) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush

