@extends('layouts.app')

@section('title', 'Appointment Calendar')

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="bi bi-calendar3"></i> Appointment Calendar
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('appointments.index') }}" class="btn-dental-outline">
            <i class="bi bi-list"></i> List View
        </a>
        <a href="{{ route('appointments.create') }}" class="btn-dental">
            <i class="bi bi-plus-circle"></i> New Appointment
        </a>
    </div>
</div>

<div class="card-dental">
    <div class="card-dental-header">
        <div class="flex justify-between items-center">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <i class="bi bi-calendar-month"></i> {{ $startDate->format('F Y') }}
            </h5>
            <div class="flex gap-2">
                <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded text-sm transition-colors">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                <a href="{{ route('calendar.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded text-sm transition-colors">
                    Today
                </a>
                <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                   class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded text-sm transition-colors">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="p-6">
        <!-- Calendar Grid -->
        <div class="flex flex-col">
            <!-- Day Headers -->
            <div class="grid grid-cols-7 gap-px bg-gray-300 border border-gray-300">
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Sun</div>
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Mon</div>
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Tue</div>
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Wed</div>
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Thu</div>
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Fri</div>
                <div class="bg-dental-teal text-white p-2.5 text-center font-bold text-sm">Sat</div>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-px bg-gray-300 border border-gray-300">
                @foreach($calendar as $day)
                    @if($day === null)
                        <div class="bg-gray-100 min-h-[120px]"></div>
                    @else
                        <div class="calendar-day bg-white min-h-[120px] p-2 relative cursor-pointer transition-colors hover:bg-gray-50
                            {{ $day['isToday'] ? 'bg-blue-50 border-2 border-blue-500' : '' }}
                            {{ $day['count'] > 0 ? 'bg-yellow-50' : '' }}
                            {{ isset($day['isBlocked']) && $day['isBlocked'] ? 'bg-red-50 opacity-70 hover:bg-red-100' : '' }}"
                             data-date="{{ $day['date']->format('Y-m-d') }}"
                             onclick="openAvailabilityModal('{{ $day['date']->format('Y-m-d') }}', '{{ $day['date']->format('M d, Y') }}')">
                            <div class="font-bold text-lg mb-1 flex items-center gap-1">
                                {{ $day['date']->format('j') }}
                                @if(isset($day['isBlocked']) && $day['isBlocked'])
                                    <i class="bi bi-x-circle-fill text-red-500 text-sm" title="Blocked"></i>
                                @endif
                            </div>
                            @if($day['count'] > 0)
                                <div class="flex flex-col gap-0.5" onclick="event.stopPropagation();">
                                    @foreach($day['appointments']->take(3) as $appointment)
                                        <div class="bg-dental-teal text-white px-1.5 py-1 rounded text-xs cursor-pointer hover:opacity-80 transition-opacity"
                                             onclick="window.location='{{ route('appointments.show', $appointment) }}'"
                                             title="{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} - {{ $appointment->appointment_date->format('g:i A') }}">
                                            <div class="flex items-center gap-1">
                                                <i class="bi bi-clock text-xs"></i>
                                                <span class="text-xs">{{ $appointment->appointment_date->format('g:i A') }}</span>
                                            </div>
                                            <div class="text-xs truncate">
                                                {{ \Illuminate\Support\Str::limit($appointment->patient->first_name . ' ' . $appointment->patient->last_name, 15) }}
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($day['count'] > 3)
                                        <div class="bg-gray-600 text-white px-1.5 py-1 rounded text-xs text-center font-bold">
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

@if(auth()->check() && auth()->user()->isDoctor())
<!-- Availability Modal -->
<div class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center" id="availabilityModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="card-dental-header flex justify-between items-center">
            <h5 class="text-lg font-semibold">Manage Availability</h5>
            <button type="button" class="text-white hover:text-gray-200 text-2xl" onclick="document.getElementById('availabilityModal').classList.add('hidden')">
                &times;
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <strong>Date:</strong> <span id="modalDate"></span>
            </div>
            
            <div id="currentAvailability" class="mb-4">
                <small class="text-gray-500">Loading...</small>
            </div>

            <hr class="my-4">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Actions:</label>
                <div class="space-y-2">
                    <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors" onclick="blockDay()">
                        <i class="bi bi-x-circle"></i> Block Entire Day
                    </button>
                    <button type="button" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors" onclick="showBlockHours()">
                        <i class="bi bi-clock"></i> Block Specific Hours
                    </button>
                    <button type="button" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors" onclick="unblockDay()">
                        <i class="bi bi-check-circle"></i> Unblock Day
                    </button>
                </div>
            </div>

            <div id="blockHoursForm" class="hidden">
                <hr class="my-4">
                <div class="mb-4">
                    <label for="blockStartTime" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <select class="input-dental" id="blockStartTime">
                        <option value="">Select start time...</option>
                    </select>
                    <small class="text-gray-500 text-xs">Times are in 30-minute intervals to match appointment slots</small>
                </div>
                <div class="mb-4">
                    <label for="blockEndTime" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <select class="input-dental" id="blockEndTime">
                        <option value="">Select end time...</option>
                    </select>
                    <small class="text-gray-500 text-xs">Select the end of the time range to block</small>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="btn-dental flex-1" onclick="blockHours()">
                        <i class="bi bi-check"></i> Block Hours
                    </button>
                    <button type="button" class="btn-dental-outline flex-1" onclick="hideBlockHours()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button type="button" class="btn-dental-outline" onclick="document.getElementById('availabilityModal').classList.add('hidden')">Close</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedDate = '';

    function openAvailabilityModal(date, dateDisplay) {
        selectedDate = date;
        document.getElementById('modalDate').textContent = dateDisplay;
        document.getElementById('blockHoursForm').classList.add('hidden');
        
        // Load current availability
        loadDateAvailability(date);
        
        // Show modal
        document.getElementById('availabilityModal').classList.remove('hidden');
        document.getElementById('availabilityModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('availabilityModal').classList.add('hidden');
        document.getElementById('availabilityModal').classList.remove('flex');
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
                return response.json().then(err => {
                    throw new Error(err.message || err.error || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('currentAvailability');
            
            if (data.error) {
                container.innerHTML = `<small class="text-red-500">Error: ${data.message || data.error}</small>`;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || data.error || 'Failed to load availability'
                });
                return;
            }
            
            if (data.availabilities && data.availabilities.length > 0) {
                const available = data.availabilities.filter(a => a.is_available === true || a.is_available === '1' || a.is_available === 1);
                const blocked = data.availabilities.filter(a => a.is_available === false || a.is_available === '0' || a.is_available === 0);
                
                blockedSlots = blocked;
                
                let html = '';
                
                if (blocked.length > 0) {
                    html += '<strong class="text-red-600">Blocked Hours:</strong><ul class="list-none mt-2 mb-3 space-y-1">';
                    blocked.forEach(avail => {
                        if (avail.start_time && avail.end_time) {
                            const startTime = formatTimeForDisplay(avail.start_time);
                            const endTime = formatTimeForDisplay(avail.end_time);
                            html += `<li class="flex items-center gap-2"><i class="bi bi-x-circle text-red-500"></i> <strong>${startTime} - ${endTime}</strong> <span class="px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Blocked</span></li>`;
                        }
                    });
                    html += '</ul>';
                }
                
                if (available.length > 0) {
                    html += '<strong class="text-green-600">Available Hours:</strong><ul class="list-none mt-2 space-y-1">';
                    available.forEach(avail => {
                        if (avail.start_time && avail.end_time) {
                            const startTime = formatTimeForDisplay(avail.start_time);
                            const endTime = formatTimeForDisplay(avail.end_time);
                            html += `<li class="flex items-center gap-2"><i class="bi bi-check-circle text-green-500"></i> ${startTime} - ${endTime} <span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Available</span></li>`;
                        }
                    });
                    html += '</ul>';
                }
                
                if (blocked.length === 0 && available.length === 0) {
                    html = '<small class="text-gray-500">No specific availability set for this date. Default hours (9 AM - 5 PM) apply.</small>';
                }
                
                container.innerHTML = html;
            } else {
                container.innerHTML = '<small class="text-gray-500">No specific availability set for this date. Default hours (9 AM - 5 PM) apply.</small>';
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load availability. Please try again.'
            });
            document.getElementById('currentAvailability').innerHTML = '<small class="text-red-500">Error loading availability. Please try again.</small>';
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

    function timeToMinutes(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        return hours * 60 + minutes;
    }

    function generateTimeSlots() {
        const slots = [];
        const startHour = 9;
        const endHour = 17;
        
        for (let hour = startHour; hour < endHour; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const time24 = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                const hour12 = hour % 12 || 12;
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const time12 = `${hour12}:${String(minute).padStart(2, '0')} ${ampm}`;
                slots.push({ value: time24, display: time12 });
            }
        }
        
        slots.push({ value: '17:00', display: '5:00 PM' });
        
        return slots;
    }

    let blockedSlots = [];

    function populateTimeSelects() {
        const slots = generateTimeSlots();
        const startSelect = document.getElementById('blockStartTime');
        const endSelect = document.getElementById('blockEndTime');
        
        const blockedRanges = getBlockedTimeRanges();
        
        const availableSlots = slots.filter(slot => {
            return !isSlotBlocked(slot.value, blockedRanges);
        });
        
        startSelect.innerHTML = '<option value="">Select start time...</option>';
        endSelect.innerHTML = '<option value="">Select end time...</option>';
        
        const startSlots = availableSlots.slice(0, -1);
        startSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.value;
            option.textContent = slot.display;
            startSelect.appendChild(option);
        });
        
        const endSlots = availableSlots.slice(1);
        endSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.value;
            option.textContent = slot.display;
            endSelect.appendChild(option);
        });
    }

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

    function isSlotBlocked(slotTime, blockedRanges) {
        if (!blockedRanges || blockedRanges.length === 0) return false;
        
        const slotMinutes = timeToMinutes(slotTime);
        
        return blockedRanges.some(range => {
            const rangeStart = timeToMinutes(range.start);
            const rangeEnd = timeToMinutes(range.end);
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
        document.getElementById('blockHoursForm').classList.remove('hidden');
        if (selectedDate) {
            loadDateAvailability(selectedDate).then(() => {
                populateTimeSelects();
            });
        } else {
            populateTimeSelects();
        }
    }

    function hideBlockHours() {
        document.getElementById('blockHoursForm').classList.add('hidden');
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
                    populateTimeSelects();
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

    // Close modal when clicking outside
    document.getElementById('availabilityModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush
@endif
@endsection
