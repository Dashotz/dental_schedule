@extends('layouts.app')

@section('title', 'My Availability')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="clock-history" class="w-8 h-8 text-dental-teal" /> My Availability
    </h2>
    <a href="{{ route('availability.create') }}" class="btn-dental">
        <x-dental-icon name="plus-circle" class="w-5 h-5" /> Add Availability
    </a>
</div>

<div class="card-dental mb-6">
    <div class="p-6">
        <h5 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
            <x-dental-icon name="info-circle" class="w-5 h-5 text-dental-teal" /> How it works
        </h5>
        <p class="text-gray-600">
            Set your weekly schedule, specific date overrides, or date ranges. Patients will only be able to book appointments during your available time slots.
        </p>
    </div>
</div>

<div class="card-dental">
    <div class="card-dental-header">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="calendar-check" class="w-5 h-5" /> Availability Schedules
        </h5>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slot Duration</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($availabilities as $availability)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($availability->type === 'weekly')
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">Weekly</span>
                                @elseif($availability->type === 'specific_date')
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-cyan-100 text-cyan-800">Specific Date</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Date Range</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
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
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $availability->slot_duration }} minutes</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($availability->is_available)
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Available</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">Blocked</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('availability.edit', $availability) }}" class="btn-dental-outline text-sm py-1.5 px-3 border-yellow-500 text-yellow-600 hover:bg-yellow-50">
                                    <x-dental-icon name="pencil" class="w-5 h-5" />
                                </a>
                                <form action="{{ route('availability.destroy', $availability) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this availability?');">
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
                            <td colspan="6" class="px-4 py-8 text-center">
                                <p class="text-gray-500 mb-3">No availability schedules set yet.</p>
                                <a href="{{ route('availability.create') }}" class="btn-dental inline-flex items-center gap-2">
                                    <x-dental-icon name="plus-circle" class="w-5 h-5" /> Create Your First Schedule
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
