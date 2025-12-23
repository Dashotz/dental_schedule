@extends('layouts.app')

@section('title', 'Appointment Calendar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar3"></i> Appointment Calendar</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
            <i class="bi bi-list"></i> List View
        </a>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Appointment
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-calendar-month"></i> {{ $startDate->format('F Y') }}
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                   class="btn btn-sm btn-light">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-light">
                    Today
                </a>
                <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                   class="btn btn-sm btn-light">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Day Headers -->
            <div class="calendar-weekdays">
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
            </div>

            <!-- Calendar Days -->
            <div class="calendar-days">
                @foreach($calendar as $day)
                    @if($day === null)
                        <div class="calendar-day empty"></div>
                    @else
                        <div class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['count'] > 0 ? 'has-appointments' : '' }} {{ isset($day['isBlocked']) && $day['isBlocked'] ? 'blocked' : '' }}"
                             data-date="{{ $day['date']->format('Y-m-d') }}"
                             onclick="openAvailabilityModal('{{ $day['date']->format('Y-m-d') }}', '{{ $day['date']->format('M d, Y') }}')">
                            <div class="calendar-day-number">
                                {{ $day['date']->format('j') }}
                                @if(isset($day['isBlocked']) && $day['isBlocked'])
                                    <i class="bi bi-x-circle-fill text-danger ms-1" title="Blocked"></i>
                                @endif
                            </div>
                            @if($day['count'] > 0)
                                <div class="calendar-appointments" onclick="event.stopPropagation();">
                                    @foreach($day['appointments']->take(3) as $appointment)
                                        <div class="calendar-appointment" 
                                             onclick="window.location='{{ route('appointments.show', $appointment) }}'"
                                             title="{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} - {{ $appointment->appointment_date->format('g:i A') }}">
                                            <small>
                                                <i class="bi bi-clock"></i> {{ $appointment->appointment_date->format('g:i A') }}
                                                <br>
                                                {{ \Illuminate\Support\Str::limit($appointment->patient->first_name . ' ' . $appointment->patient->last_name, 15) }}
                                            </small>
                                        </div>
                                    @endforeach
                                    @if($day['count'] > 3)
                                        <div class="calendar-appointment-more">
                                            +{{ $day['count'] - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .calendar-grid {
        display: flex;
        flex-direction: column;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #dee2e6;
        border: 1px solid #dee2e6;
    }

    .calendar-day-header {
        background-color: #0d6efd;
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #dee2e6;
        border: 1px solid #dee2e6;
    }

    .calendar-day {
        min-height: 120px;
        background-color: white;
        padding: 8px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .calendar-day:hover {
        background-color: #f8f9fa;
    }

    .calendar-day.today {
        background-color: #e7f3ff;
        border: 2px solid #0d6efd;
    }

    .calendar-day.has-appointments {
        background-color: #fff3cd;
    }

    .calendar-day.blocked {
        background-color: #f8d7da;
        opacity: 0.7;
    }

    .calendar-day.blocked:hover {
        background-color: #f5c2c7;
    }

    .calendar-day.empty {
        background-color: #f8f9fa;
        cursor: default;
    }

    .calendar-day-number {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .calendar-appointments {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .calendar-appointment {
        background-color: #0d6efd;
        color: white;
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .calendar-appointment:hover {
        opacity: 0.8;
    }

    .calendar-appointment-more {
        background-color: #6c757d;
        color: white;
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        text-align: center;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 4px;
        }

        .calendar-day-number {
            font-size: 0.9rem;
        }

        .calendar-appointment {
            font-size: 0.65rem;
            padding: 2px 4px;
        }

        .calendar-day-header {
            padding: 5px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@if(auth()->check() && auth()->user()->isDoctor())
<!-- Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel">Manage Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Date:</strong> <span id="modalDate"></span>
                </div>
                
                <div id="currentAvailability" class="mb-3">
                    <small class="text-muted">Loading...</small>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label">Quick Actions:</label>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger" onclick="blockDay()">
                            <i class="bi bi-x-circle"></i> Block Entire Day
                        </button>
                        <button type="button" class="btn btn-warning" onclick="showBlockHours()">
                            <i class="bi bi-clock"></i> Block Specific Hours
                        </button>
                        <button type="button" class="btn btn-success" onclick="unblockDay()">
                            <i class="bi bi-check-circle"></i> Unblock Day
                        </button>
                    </div>
                </div>

                <div id="blockHoursForm" style="display: none;">
                    <hr>
                    <div class="mb-3">
                        <label for="blockStartTime" class="form-label">Start Time</label>
                        <select class="form-select" id="blockStartTime">
                            <option value="">Select start time...</option>
                        </select>
                        <small class="text-muted">Times are in 30-minute intervals to match appointment slots</small>
                    </div>
                    <div class="mb-3">
                        <label for="blockEndTime" class="form-label">End Time</label>
                        <select class="form-select" id="blockEndTime">
                            <option value="">Select end time...</option>
                        </select>
                        <small class="text-muted">Select the end of the time range to block</small>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="blockHours()">
                        <i class="bi bi-check"></i> Block Hours
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="hideBlockHours()">
                        Cancel
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedDate = '';

    function openAvailabilityModal(date, dateDisplay) {
        selectedDate = date;
        document.getElementById('modalDate').textContent = dateDisplay;
        document.getElementById('blockHoursForm').style.display = 'none';
        
        // Load current availability
        loadDateAvailability(date);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('availabilityModal'));
        modal.show();
    }

    function loadDateAvailability(date) {
        return fetch(`/availability/date-availability?date=${date}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                // If response is not ok, try to parse error
                return response.json().then(err => {
                    throw new Error(err.message || err.error || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('currentAvailability');
            
            // Check for error in response
            if (data.error) {
                container.innerHTML = `<small class="text-danger">Error: ${data.message || data.error}</small>`;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || data.error || 'Failed to load availability'
                });
                return;
            }
            
            if (data.availabilities && data.availabilities.length > 0) {
                // Separate available and blocked entries
                // Handle both boolean and string values
                const available = data.availabilities.filter(a => a.is_available === true || a.is_available === '1' || a.is_available === 1);
                const blocked = data.availabilities.filter(a => a.is_available === false || a.is_available === '0' || a.is_available === 0);
                
                // Store blocked slots globally for filtering dropdowns
                blockedSlots = blocked;
                
                // Store blocked slots globally for filtering dropdowns
                blockedSlots = blocked;
                
                let html = '';
                
                if (blocked.length > 0) {
                    html += '<strong class="text-danger">Blocked Hours:</strong><ul class="list-unstyled mt-2 mb-3">';
                    blocked.forEach(avail => {
                        if (avail.start_time && avail.end_time) {
                            const startTime = formatTimeForDisplay(avail.start_time);
                            const endTime = formatTimeForDisplay(avail.end_time);
                            html += `<li class="mb-1"><i class="bi bi-x-circle text-danger"></i> <strong>${startTime} - ${endTime}</strong> <span class="badge bg-danger">Blocked</span></li>`;
                        }
                    });
                    html += '</ul>';
                }
                
                if (available.length > 0) {
                    html += '<strong class="text-success">Available Hours:</strong><ul class="list-unstyled mt-2">';
                    available.forEach(avail => {
                        if (avail.start_time && avail.end_time) {
                            const startTime = formatTimeForDisplay(avail.start_time);
                            const endTime = formatTimeForDisplay(avail.end_time);
                            html += `<li class="mb-1"><i class="bi bi-check-circle text-success"></i> ${startTime} - ${endTime} <span class="badge bg-success">Available</span></li>`;
                        }
                    });
                    html += '</ul>';
                }
                
                if (blocked.length === 0 && available.length === 0) {
                    html = '<small class="text-muted">No specific availability set for this date. Default hours (9 AM - 5 PM) apply.</small>';
                }
                
                container.innerHTML = html;
            } else {
                container.innerHTML = '<small class="text-muted">No specific availability set for this date. Default hours (9 AM - 5 PM) apply.</small>';
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load availability. Please try again.'
            });
            document.getElementById('currentAvailability').innerHTML = '<small class="text-danger">Error loading availability. Please try again.</small>';
        });
    }

    function formatTimeForDisplay(timeString) {
        if (!timeString) return '';
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }

    // Convert time string (HH:MM) to minutes for comparison
    function timeToMinutes(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        return hours * 60 + minutes;
    }

    // Generate 30-minute time slots (matching registration form)
    function generateTimeSlots() {
        const slots = [];
        const startHour = 9; // 9 AM
        const endHour = 17; // 5 PM (17:00)
        
        for (let hour = startHour; hour < endHour; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const time24 = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                const hour12 = hour % 12 || 12;
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const time12 = `${hour12}:${String(minute).padStart(2, '0')} ${ampm}`;
                slots.push({ value: time24, display: time12 });
            }
        }
        
        // Add the end time (5:00 PM)
        slots.push({ value: '17:00', display: '5:00 PM' });
        
        return slots;
    }

    // Store blocked slots globally to filter dropdowns
    let blockedSlots = [];

    // Populate time select dropdowns with 30-minute intervals (excluding already blocked slots)
    function populateTimeSelects() {
        const slots = generateTimeSlots();
        const startSelect = document.getElementById('blockStartTime');
        const endSelect = document.getElementById('blockEndTime');
        
        // Get blocked time ranges from current availability data
        const blockedRanges = getBlockedTimeRanges();
        
        // Filter out slots that are already blocked
        const availableSlots = slots.filter(slot => {
            return !isSlotBlocked(slot.value, blockedRanges);
        });
        
        // Clear existing options (except first)
        startSelect.innerHTML = '<option value="">Select start time...</option>';
        endSelect.innerHTML = '<option value="">Select end time...</option>';
        
        // Populate start time (exclude last slot as it's the end time, and already blocked slots)
        const startSlots = availableSlots.slice(0, -1);
        startSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.value;
            option.textContent = slot.display;
            startSelect.appendChild(option);
        });
        
        // Populate end time (exclude first slot, start from second, and already blocked slots)
        const endSlots = availableSlots.slice(1);
        endSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.value;
            option.textContent = slot.display;
            endSelect.appendChild(option);
        });
    }

    // Get blocked time ranges from the availability data
    function getBlockedTimeRanges() {
        const ranges = [];
        if (blockedSlots && blockedSlots.length > 0) {
            blockedSlots.forEach(block => {
                if (block.start_time && block.end_time) {
                    ranges.push({
                        start: block.start_time,
                        end: block.end_time
                    });
                }
            });
        }
        return ranges;
    }

    // Check if a time slot is blocked
    function isSlotBlocked(slotTime, blockedRanges) {
        if (!blockedRanges || blockedRanges.length === 0) return false;
        
        const slotMinutes = timeToMinutes(slotTime);
        
        return blockedRanges.some(range => {
            const rangeStart = timeToMinutes(range.start);
            const rangeEnd = timeToMinutes(range.end);
            // Check if slot time falls within any blocked range
            return slotMinutes >= rangeStart && slotMinutes < rangeEnd;
        });
    }

    function blockDay() {
        Swal.fire({
            icon: 'question',
            title: 'Block Entire Day?',
            text: 'Are you sure you want to block the entire day?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, block it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            performBlockDay();
        });
    }
    
    function performBlockDay() {

        fetch('/availability/quick-set', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                date: selectedDate,
                action: 'block_day'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                loadDateAvailability(selectedDate);
                setTimeout(() => location.reload(), 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Failed to block day'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while blocking the day. Please try again.'
            });
        });
    }

    function showBlockHours() {
        document.getElementById('blockHoursForm').style.display = 'block';
        // Refresh availability data first, then populate selects
        if (selectedDate) {
            loadDateAvailability(selectedDate).then(() => {
                populateTimeSelects();
            });
        } else {
            populateTimeSelects();
        }
    }

    function hideBlockHours() {
        document.getElementById('blockHoursForm').style.display = 'none';
        document.getElementById('blockStartTime').value = '';
        document.getElementById('blockEndTime').value = '';
    }

    function blockHours() {
        const startTime = document.getElementById('blockStartTime').value;
        const endTime = document.getElementById('blockEndTime').value;

        if (!startTime || !endTime) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select both start and end times'
            });
            return;
        }

        // Convert to comparable format
        const startMinutes = timeToMinutes(startTime);
        const endMinutes = timeToMinutes(endTime);

        if (startMinutes >= endMinutes) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'End time must be after start time'
            });
            return;
        }
        
        // Validate minimum block duration (at least 30 minutes)
        if (endMinutes - startMinutes < 30) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Minimum block duration is 30 minutes'
            });
            return;
        }
        
        // Validate minimum block duration (at least 30 minutes)
        if (endMinutes - startMinutes < 30) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Minimum block duration is 30 minutes'
            });
            return;
        }

        fetch('/availability/quick-set', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                date: selectedDate,
                action: 'block_hours',
                start_time: startTime,
                end_time: endTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                hideBlockHours();
                loadDateAvailability(selectedDate).then(() => {
                    populateTimeSelects(); // Refresh dropdowns to hide newly blocked slots
                });
                setTimeout(() => location.reload(), 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Failed to block hours'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while blocking hours. Please try again.'
            });
        });
    }

    function unblockDay() {
        Swal.fire({
            icon: 'question',
            title: 'Unblock Day?',
            text: 'Are you sure you want to unblock this day?',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes, unblock it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch('/availability/quick-set', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    date: selectedDate,
                    action: 'unblock'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadDateAvailability(selectedDate);
                    // Refresh time selects to show newly available slots
                    populateTimeSelects();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Failed to unblock day'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while unblocking the day. Please try again.'
                });
            });
        });
    }
</script>
@endpush
@endif
@endsection

