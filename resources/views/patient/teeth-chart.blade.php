@extends('layouts.app')

@section('title', 'Dental Chart - ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-teeth"></i> Dental Chart - {{ $patient->first_name }} {{ $patient->last_name }}</h2>
    <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Patient
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-grid"></i> Teeth Chart</h5>
            </div>
            <div class="card-body">
                <!-- Upper Jaw (Maxilla) -->
                <div class="jaw-section mb-4">
                    <h6 class="text-center mb-4"><i class="bi bi-arrow-up"></i> Upper Jaw (Maxilla)</h6>
                    <div class="d-flex justify-content-center">
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
                <div class="jaw-section">
                    <h6 class="text-center mb-4"><i class="bi bi-arrow-down"></i> Lower Jaw (Mandible)</h6>
                    <div class="d-flex justify-content-center">
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
    </div>
</div>

<!-- Teeth Records Modal -->
<div class="modal fade" id="teethRecordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tooth #<span id="modalToothNumber"></span> - Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="teethRecordForm">
                <div class="modal-body">
                    <input type="hidden" id="tooth_number" name="tooth_number">
                    
                    <div class="mb-3">
                        <label for="condition" class="form-label">Condition</label>
                        <select class="form-select" id="condition" name="condition">
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

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="4" placeholder="Enter remarks about this tooth..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/teeth-chart.css') }}">
@endpush

@push('scripts')
<script>
    window.patientId = {{ $patient->id }};
</script>
<script src="{{ asset('js/teeth-chart.js') }}"></script>
@endpush
@endsection
