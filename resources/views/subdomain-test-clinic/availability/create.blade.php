@extends('layouts.app')

@section('title', 'Add Availability')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><x-dental-icon name="plus-circle" class="w-5 h-5" /> Add Availability</h2>
    <a href="{{ route('availability.index') }}" class="btn btn-secondary">
        <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('availability.store') }}" method="POST" id="availabilityForm">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label">Schedule Type <span class="text-danger">*</span></label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Select type...</option>
                    <option value="weekly" {{ old('type') === 'weekly' ? 'selected' : '' }}>Weekly (Recurring)</option>
                    <option value="specific_date" {{ old('type') === 'specific_date' ? 'selected' : '' }}>Specific Date</option>
                    <option value="date_range" {{ old('type') === 'date_range' ? 'selected' : '' }}>Date Range</option>
                </select>
                <small class="form-text text-muted">Weekly: Repeats every week on the selected day. Specific Date: One-time schedule. Date Range: Available for a period.</small>
            </div>

            <!-- Weekly Options -->
            <div class="mb-3" id="weeklyOptions" style="display: none;">
                <label for="day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                <select class="form-select" id="day_of_week" name="day_of_week">
                    <option value="">Select day...</option>
                    <option value="0" {{ old('day_of_week') == '0' ? 'selected' : '' }}>Sunday</option>
                    <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Monday</option>
                    <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Tuesday</option>
                    <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Wednesday</option>
                    <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Thursday</option>
                    <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Friday</option>
                    <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Saturday</option>
                </select>
            </div>

            <!-- Specific Date Options -->
            <div class="mb-3" id="specificDateOptions" style="display: none;">
                <label for="specific_date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="specific_date" name="specific_date" value="{{ old('specific_date') }}" min="{{ date('Y-m-d') }}">
            </div>

            <!-- Date Range Options -->
            <div class="row mb-3" id="dateRangeOptions" style="display: none;">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', '17:00') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="slot_duration" class="form-label">Slot Duration (minutes) <span class="text-danger">*</span></label>
                <select class="form-select" id="slot_duration" name="slot_duration" required>
                    <option value="15" {{ old('slot_duration') == '15' ? 'selected' : '' }}>15 minutes</option>
                    <option value="30" {{ old('slot_duration') == '30' || !old('slot_duration') ? 'selected' : '' }}>30 minutes</option>
                    <option value="45" {{ old('slot_duration') == '45' ? 'selected' : '' }}>45 minutes</option>
                    <option value="60" {{ old('slot_duration') == '60' ? 'selected' : '' }}>60 minutes</option>
                </select>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_available">
                        Available (uncheck to block this time)
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this availability...">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('availability.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <x-dental-icon name="check-circle" class="w-5 h-5" /> Create Availability
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const weeklyOptions = document.getElementById('weeklyOptions');
        const specificDateOptions = document.getElementById('specificDateOptions');
        const dateRangeOptions = document.getElementById('dateRangeOptions');
        const dayOfWeek = document.getElementById('day_of_week');
        const specificDate = document.getElementById('specific_date');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        function toggleOptions() {
            const type = typeSelect.value;
            
            // Hide all options
            weeklyOptions.style.display = 'none';
            specificDateOptions.style.display = 'none';
            dateRangeOptions.style.display = 'none';
            
            // Clear required attributes
            dayOfWeek.removeAttribute('required');
            specificDate.removeAttribute('required');
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');
            
            // Show relevant options
            if (type === 'weekly') {
                weeklyOptions.style.display = 'block';
                dayOfWeek.setAttribute('required', 'required');
            } else if (type === 'specific_date') {
                specificDateOptions.style.display = 'block';
                specificDate.setAttribute('required', 'required');
            } else if (type === 'date_range') {
                dateRangeOptions.style.display = 'block';
                startDate.setAttribute('required', 'required');
                endDate.setAttribute('required', 'required');
            }
        }

        typeSelect.addEventListener('change', toggleOptions);
        
        // Initialize on page load
        toggleOptions();
    });
</script>
@endpush
@endsection

