@extends('layouts.app')

@section('title', 'Dental Chart - ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="tooth" class="w-5 h-5" /> Dental Chart - {{ $patient->first_name }} {{ $patient->last_name }}
    </h2>
    <a href="{{ route('patients.show', $patient) }}" class="btn-dental-outline">
        <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back to Patient
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
        <div class="mb-8">
            <h6 class="text-center mb-4 text-gray-700 font-semibold flex items-center justify-center gap-2">
                <x-dental-icon name="arrow-up" class="w-5 h-5" /> Upper Jaw (Maxilla)
            </h6>
            <div class="flex justify-center overflow-x-auto">
                <svg width="1000" height="140" viewBox="0 0 1000 140" class="jaw-svg">
                    <!-- Upper Right Quadrant (18-11) - from patient's right to center -->
                    @php
                        $upperRightTeeth = [18, 17, 16, 15, 14, 13, 12, 11];
                        $spacing = 110;
                        $startX = 30;
                        $y = 70;
                    @endphp
                    @foreach($upperRightTeeth as $index => $toothNum)
                        @php
                            $x = $startX + ($index * $spacing);
                        @endphp
                        @include('patient.partials.tooth-svg', [
                            'toothNumber' => $toothNum,
                            'x' => $x,
                            'y' => $y,
                            'teethRecords' => $teethRecords
                        ])
                    @endforeach
                    
                    <!-- Upper Left Quadrant (21-28) - from center to patient's left -->
                    @php
                        $upperLeftTeeth = [21, 22, 23, 24, 25, 26, 27, 28];
                    @endphp
                    @foreach($upperLeftTeeth as $index => $toothNum)
                        @php
                            $x = $startX + (($index + 8) * $spacing);
                        @endphp
                        @include('patient.partials.tooth-svg', [
                            'toothNumber' => $toothNum,
                            'x' => $x,
                            'y' => $y,
                            'teethRecords' => $teethRecords
                        ])
                    @endforeach
                </svg>
            </div>
        </div>

        <!-- Lower Jaw (Mandible) -->
        <div>
            <h6 class="text-center mb-4 text-gray-700 font-semibold flex items-center justify-center gap-2">
                <x-dental-icon name="arrow-down" class="w-5 h-5" /> Lower Jaw (Mandible)
            </h6>
            <div class="flex justify-center overflow-x-auto">
                <svg width="1000" height="140" viewBox="0 0 1000 140" class="jaw-svg">
                    <!-- Lower Right Quadrant (48-41) - from patient's right to center -->
                    @php
                        $lowerRightTeeth = [48, 47, 46, 45, 44, 43, 42, 41];
                        $spacing = 110;
                        $startX = 30;
                        $y = 70;
                    @endphp
                    @foreach($lowerRightTeeth as $index => $toothNum)
                        @php
                            $x = $startX + ($index * $spacing);
                        @endphp
                        @include('patient.partials.tooth-svg', [
                            'toothNumber' => $toothNum,
                            'x' => $x,
                            'y' => $y,
                            'teethRecords' => $teethRecords
                        ])
                    @endforeach
                    
                    <!-- Lower Left Quadrant (31-38) - from center to patient's left -->
                    @php
                        $lowerLeftTeeth = [31, 32, 33, 34, 35, 36, 37, 38];
                    @endphp
                    @foreach($lowerLeftTeeth as $index => $toothNum)
                        @php
                            $x = $startX + (($index + 8) * $spacing);
                        @endphp
                        @include('patient.partials.tooth-svg', [
                            'toothNumber' => $toothNum,
                            'x' => $x,
                            'y' => $y,
                            'teethRecords' => $teethRecords
                        ])
                    @endforeach
                </svg>
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

{{-- Styles migrated to Tailwind in app.css --}}

@push('scripts')
<script>
    window.patientId = {{ $patient->id }};
</script>
<script src="{{ asset('js/teeth-chart.js') }}"></script>
@endpush
@endsection
