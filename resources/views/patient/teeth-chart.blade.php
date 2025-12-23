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
                        <svg width="800" height="180" viewBox="0 0 800 180" class="jaw-svg">
                            <!-- Upper jaw arch path -->
                            <path d="M 80,140 Q 200,60 400,50 Q 600,60 720,140" 
                                  fill="none" 
                                  stroke="#e0e0e0" 
                                  stroke-width="2" 
                                  stroke-dasharray="4,4" 
                                  opacity="0.5"/>
                            
                            <!-- Right side teeth (18-11) -->
                            @php
                                $rightUpperTeeth = [18, 17, 16, 15, 14, 13, 12, 11];
                                $spacing = 85;
                                $startX = 80;
                                $baseY = 140;
                            @endphp
                            @foreach($rightUpperTeeth as $index => $toothNum)
                                @php
                                    $x = $startX + ($index * $spacing);
                                    $curveOffset = abs($index - 3.5) * 8;
                                    $y = $baseY - $curveOffset;
                                @endphp
                                @include('patient.partials.tooth-svg', [
                                    'toothNumber' => $toothNum,
                                    'x' => $x,
                                    'y' => $y,
                                    'teethRecords' => $teethRecords
                                ])
                            @endforeach
                            
                            <!-- Left side teeth (21-28) -->
                            @php
                                $leftUpperTeeth = [21, 22, 23, 24, 25, 26, 27, 28];
                                $startX = 400;
                            @endphp
                            @foreach($leftUpperTeeth as $index => $toothNum)
                                @php
                                    $x = $startX + ($index * $spacing);
                                    $curveOffset = abs($index - 3.5) * 8;
                                    $y = 50 + $curveOffset;
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
                        <svg width="800" height="180" viewBox="0 0 800 180" class="jaw-svg">
                            <!-- Lower jaw arch path -->
                            <path d="M 80,40 Q 200,120 400,130 Q 600,120 720,40" 
                                  fill="none" 
                                  stroke="#e0e0e0" 
                                  stroke-width="2" 
                                  stroke-dasharray="4,4" 
                                  opacity="0.5"/>
                            
                            <!-- Right side teeth (48-41) -->
                            @php
                                $rightLowerTeeth = [48, 47, 46, 45, 44, 43, 42, 41];
                                $spacing = 85;
                                $startX = 80;
                                $baseY = 40;
                            @endphp
                            @foreach($rightLowerTeeth as $index => $toothNum)
                                @php
                                    $x = $startX + ($index * $spacing);
                                    $curveOffset = abs($index - 3.5) * 8;
                                    $y = $baseY + $curveOffset;
                                @endphp
                                @include('patient.partials.tooth-svg', [
                                    'toothNumber' => $toothNum,
                                    'x' => $x,
                                    'y' => $y,
                                    'teethRecords' => $teethRecords
                                ])
                            @endforeach
                            
                            <!-- Left side teeth (31-38) -->
                            @php
                                $leftLowerTeeth = [31, 32, 33, 34, 35, 36, 37, 38];
                                $startX = 400;
                            @endphp
                            @foreach($leftLowerTeeth as $index => $toothNum)
                                @php
                                    $x = $startX + ($index * $spacing);
                                    $curveOffset = abs($index - 3.5) * 8;
                                    $y = 130 - $curveOffset;
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

                <!-- Legend -->
                <div class="mt-4">
                    <h6 class="mb-3">Condition Legend</h6>
                    <div class="row g-2">
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color healthy"></div>
                                <span>Healthy</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color cavity"></div>
                                <span>Cavity</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color filling"></div>
                                <span>Filling</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color crown"></div>
                                <span>Crown</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color extracted"></div>
                                <span>Extracted/Missing</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color impacted"></div>
                                <span>Impacted</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color root_canal"></div>
                                <span>Root Canal</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="legend-item">
                                <div class="legend-color other"></div>
                                <span>Other</span>
                            </div>
                        </div>
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
<style>
    .jaw-section {
        background: #f8f9fa;
        padding: 40px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
    }

    .jaw-svg {
        max-width: 100%;
        height: auto;
        background: transparent;
    }

    .tooth-svg {
        transition: all 0.2s ease;
    }

    .tooth-svg:hover {
        transform: scale(1.1);
        filter: brightness(1.05);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid #333;
    }

    .legend-color.healthy {
        background: #d4edda;
        border-color: #28a745;
    }

    .legend-color.cavity {
        background: #f8d7da;
        border-color: #dc3545;
    }

    .legend-color.filling {
        background: #cfe2ff;
        border-color: #0d6efd;
    }

    .legend-color.crown {
        background: #e7d4f8;
        border-color: #6f42c1;
    }

    .legend-color.extracted {
        background: #6c757d;
        border-color: #495057;
    }

    .legend-color.impacted {
        background: #ffebcd;
        border-color: #ff8c00;
    }

    .legend-color.root_canal {
        background: #d1ecf1;
        border-color: #17a2b8;
    }

    .legend-color.other {
        background: #fff3cd;
        border-color: #ffc107;
    }

    @media (max-width: 768px) {
        .jaw-svg {
            width: 100%;
            height: 140px;
        }
        
        .jaw-section {
            padding: 25px 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        const modal = new bootstrap.Modal(document.getElementById('teethRecordModal'));
        const form = $('#teethRecordForm');
        const patientId = {{ $patient->id }};

        $('.tooth-svg').on('click', function() {
            const toothNumber = $(this).data('tooth');
            $('#modalToothNumber').text(toothNumber);
            $('#tooth_number').val(toothNumber);
            
            $.ajax({
                url: `/patients/${patientId}/teeth-records/${toothNumber}`,
                method: 'GET',
                success: function(response) {
                    if (response.record) {
                        $('#condition').val(response.record.condition || '');
                        $('#remarks').val(response.record.remarks || '');
                    } else {
                        $('#condition').val('');
                        $('#remarks').val('');
                    }
                    modal.show();
                },
                error: function() {
                    $('#condition').val('');
                    $('#remarks').val('');
                    modal.show();
                }
            });
        });

        form.on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                tooth_number: $('#tooth_number').val(),
                condition: $('#condition').val(),
                remarks: $('#remarks').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: `/patients/${patientId}/teeth-records`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    modal.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to save record.'
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection
