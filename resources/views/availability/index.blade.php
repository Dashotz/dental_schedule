@extends('layouts.app')

@section('title', 'My Availability')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clock-history"></i> My Availability</h2>
    <a href="{{ route('availability.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Availability
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">How it works</h5>
                <p class="card-text text-muted">
                    Set your weekly schedule, specific date overrides, or date ranges. Patients will only be able to book appointments during your available time slots.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Schedule</th>
                        <th>Time</th>
                        <th>Slot Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($availabilities as $availability)
                        <tr>
                            <td>
                                @if($availability->type === 'weekly')
                                    <span class="badge bg-primary">Weekly</span>
                                @elseif($availability->type === 'specific_date')
                                    <span class="badge bg-info">Specific Date</span>
                                @else
                                    <span class="badge bg-success">Date Range</span>
                                @endif
                            </td>
                            <td>
                                @if($availability->type === 'weekly')
                                    @php
                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    @endphp
                                    <strong>{{ $days[$availability->day_of_week] }}</strong>
                                @elseif($availability->type === 'specific_date')
                                    <strong>{{ $availability->specific_date->format('M d, Y') }}</strong>
                                @else
                                    <strong>{{ $availability->start_date->format('M d, Y') }}</strong> - 
                                    <strong>{{ $availability->end_date->format('M d, Y') }}</strong>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                            </td>
                            <td>{{ $availability->slot_duration }} minutes</td>
                            <td>
                                @if($availability->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('availability.edit', $availability) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('availability.destroy', $availability) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <p class="text-muted mb-3">No availability schedules set yet.</p>
                                <a href="{{ route('availability.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create Your First Schedule
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

