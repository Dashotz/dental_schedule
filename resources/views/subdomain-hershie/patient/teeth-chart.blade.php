@extends('layouts.app')

@section('title', 'Dental Chart - ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="tooth" class="w-8 h-8 text-dental-teal" /> Dental Chart - {{ $patient->first_name }} {{ $patient->last_name }}
    </h2>
    <a href="{{ route('patients.index') }}" class="btn-dental-outline">
        <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back to Patients
    </a>
</div>

<div class="card-dental">
    <div class="card-dental-header">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="grid" class="w-5 h-5" /> Teeth Chart
        </h5>
    </div>
    <div class="p-6">
        <!-- Upper Jaw (Maxilla) -->
        <div class="mb-12">
            <h6 class="text-center mb-6 text-gray-700 font-semibold flex items-center justify-center gap-2">
                <x-dental-icon name="arrow-up" class="w-5 h-5 text-dental-teal" /> Upper Jaw (Maxilla)
            </h6>
            <div class="flex justify-center flex-wrap gap-4 max-w-6xl mx-auto">
                @php
                    $upperRightTeeth = [18, 17, 16, 15, 14, 13, 12, 11];
                    $upperLeftTeeth = [21, 22, 23, 24, 25, 26, 27, 28];
                    $allUpperTeeth = array_merge($upperRightTeeth, $upperLeftTeeth);
                @endphp
                @foreach($allUpperTeeth as $toothNum)
                    @include('patient.partials.tooth', [
                        'toothNumber' => $toothNum,
                        'teethRecords' => $teethRecords
                    ])
                @endforeach
            </div>
        </div>

        <!-- Lower Jaw (Mandible) -->
        <div>
            <h6 class="text-center mb-6 text-gray-700 font-semibold flex items-center justify-center gap-2">
                <x-dental-icon name="arrow-down" class="w-5 h-5 text-dental-teal" /> Lower Jaw (Mandible)
            </h6>
            <div class="flex justify-center flex-wrap gap-4 max-w-6xl mx-auto">
                @php
                    $lowerRightTeeth = [48, 47, 46, 45, 44, 43, 42, 41];
                    $lowerLeftTeeth = [31, 32, 33, 34, 35, 36, 37, 38];
                    $allLowerTeeth = array_merge($lowerRightTeeth, $lowerLeftTeeth);
                @endphp
                @foreach($allLowerTeeth as $toothNum)
                    @include('patient.partials.tooth', [
                        'toothNumber' => $toothNum,
                        'teethRecords' => $teethRecords
                    ])
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Teeth Records Modal -->
<x-modal id="teethRecordModal" title="Tooth #<span id='modalToothNumber'></span> - Record" size="md">
    <form id="teethRecordForm">
        <input type="hidden" id="tooth_number" name="tooth_number">
        
        <div class="mb-4">
            <label for="condition" class="form-label">Condition</label>
            <select class="input-dental" id="condition" name="condition">
                <option value="">Select Condition...</option>
                <option value="healthy">Healthy</option>
                <option value="cavity">Cavity</option>
                <option value="filling">Filling</option>
                <option value="crown">Crown</option>
                <option value="extracted">Extracted</option>
                <option value="missing">Missing</option>
                <option value="impacted">Impacted</option>
                <option value="root_canal">Root Canal</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="input-dental" id="remarks" name="remarks" rows="4" placeholder="Enter remarks about this tooth..."></textarea>
        </div>
    </form>
    
    <x-slot name="footer">
        <button type="button" class="btn-dental-outline" onclick="closeModal('teethRecordModal')">Close</button>
        <button type="submit" form="teethRecordForm" class="btn-dental">Save Record</button>
    </x-slot>
</x-modal>

@push('scripts')
<script>
    window.patientId = {{ $patient->id }};
</script>
<script src="{{ asset('js/teeth-chart.js') }}"></script>
@endpush
@endsection
