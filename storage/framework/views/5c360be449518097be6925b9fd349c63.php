<?php $__env->startSection('title', 'Dental Chart - ' . $patient->first_name . ' ' . $patient->last_name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-teeth"></i> Dental Chart - <?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></h2>
    <a href="<?php echo e(route('patients.show', $patient)); ?>" class="btn btn-secondary">
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
                <div class="jaw-section mb-5">
                    <h6 class="text-center mb-3"><i class="bi bi-arrow-up"></i> Upper Jaw (Maxilla)</h6>
                    <div class="d-flex justify-content-center">
                        <svg width="700" height="200" viewBox="0 0 700 200" class="jaw-svg">
                            <!-- Upper jaw arch path (curved like real jaw) -->
                            <path d="M 50,150 Q 175,80 350,70 Q 525,80 650,150" 
                                  fill="none" 
                                  stroke="#d0d0d0" 
                                  stroke-width="2" 
                                  stroke-dasharray="5,5" 
                                  opacity="0.4"/>
                            
                            <!-- Right side teeth (18-11) - positioned along curved arch -->
                            <?php
                                $rightUpperTeeth = [18, 17, 16, 15, 14, 13, 12, 11];
                            ?>
                            <?php $__currentLoopData = $rightUpperTeeth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $toothNum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Calculate position along the arch curve
                                    $t = $index / 7; // 0 to 1
                                    $archX = 50 + ($t * 300); // 50 to 350
                                    $archY = 150 - (80 * (1 - pow($t - 0.5, 2) * 4)); // Curved
                                    $x = $archX;
                                    $y = $archY;
                                ?>
                                <?php echo $__env->make('patient.partials.tooth-svg', [
                                    'toothNumber' => $toothNum,
                                    'x' => $x,
                                    'y' => $y,
                                    'teethRecords' => $teethRecords
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- Left side teeth (21-28) - positioned along curved arch -->
                            <?php
                                $leftUpperTeeth = [21, 22, 23, 24, 25, 26, 27, 28];
                            ?>
                            <?php $__currentLoopData = $leftUpperTeeth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $toothNum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Calculate position along the arch curve
                                    $t = $index / 7; // 0 to 1
                                    $archX = 350 + ($t * 300); // 350 to 650
                                    $archY = 70 + (80 * (1 - pow($t - 0.5, 2) * 4)); // Curved
                                    $x = $archX;
                                    $y = $archY;
                                ?>
                                <?php echo $__env->make('patient.partials.tooth-svg', [
                                    'toothNumber' => $toothNum,
                                    'x' => $x,
                                    'y' => $y,
                                    'teethRecords' => $teethRecords
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </svg>
                    </div>
                </div>

                <!-- Lower Jaw (Mandible) -->
                <div class="jaw-section">
                    <h6 class="text-center mb-3"><i class="bi bi-arrow-down"></i> Lower Jaw (Mandible)</h6>
                    <div class="d-flex justify-content-center">
                        <svg width="700" height="200" viewBox="0 0 700 200" class="jaw-svg">
                            <!-- Lower jaw arch path (curved like real jaw) -->
                            <path d="M 50,50 Q 175,120 350,130 Q 525,120 650,50" 
                                  fill="none" 
                                  stroke="#d0d0d0" 
                                  stroke-width="2" 
                                  stroke-dasharray="5,5" 
                                  opacity="0.4"/>
                            
                            <!-- Right side teeth (48-41) - positioned along curved arch -->
                            <?php
                                $rightLowerTeeth = [48, 47, 46, 45, 44, 43, 42, 41];
                            ?>
                            <?php $__currentLoopData = $rightLowerTeeth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $toothNum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Calculate position along the arch curve
                                    $t = $index / 7; // 0 to 1
                                    $archX = 50 + ($t * 300); // 50 to 350
                                    $archY = 50 + (80 * (1 - pow($t - 0.5, 2) * 4)); // Curved
                                    $x = $archX;
                                    $y = $archY;
                                ?>
                                <?php echo $__env->make('patient.partials.tooth-svg', [
                                    'toothNumber' => $toothNum,
                                    'x' => $x,
                                    'y' => $y,
                                    'teethRecords' => $teethRecords
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- Left side teeth (31-38) - positioned along curved arch -->
                            <?php
                                $leftLowerTeeth = [31, 32, 33, 34, 35, 36, 37, 38];
                            ?>
                            <?php $__currentLoopData = $leftLowerTeeth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $toothNum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Calculate position along the arch curve
                                    $t = $index / 7; // 0 to 1
                                    $archX = 350 + ($t * 300); // 350 to 650
                                    $archY = 130 - (80 * (1 - pow($t - 0.5, 2) * 4)); // Curved
                                    $x = $archX;
                                    $y = $archY;
                                ?>
                                <?php echo $__env->make('patient.partials.tooth-svg', [
                                    'toothNumber' => $toothNum,
                                    'x' => $x,
                                    'y' => $y,
                                    'teethRecords' => $teethRecords
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php $__env->startPush('styles'); ?>
<style>
    .jaw-section {
        background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px 20px;
        border-radius: 15px;
        margin-bottom: 30px;
    }

    .jaw-svg {
        max-width: 100%;
        height: auto;
        background: transparent;
    }

    .tooth-svg {
        transition: all 0.3s ease;
    }

    .tooth-svg:hover {
        transform: scale(1.15);
        filter: brightness(1.1);
    }

    .tooth-svg path,
    .tooth-svg rect {
        transition: all 0.3s ease;
    }

    .tooth-svg:hover path,
    .tooth-svg:hover rect {
        stroke-width: 2.5;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
    }

    @media (max-width: 768px) {
        .jaw-svg {
            width: 100%;
            height: 150px;
        }
        
        .jaw-section {
            padding: 20px 10px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        const modal = new bootstrap.Modal(document.getElementById('teethRecordModal'));
        const form = $('#teethRecordForm');
        const patientId = <?php echo e($patient->id); ?>;

        // Handle tooth click
        $('.tooth-svg').on('click', function() {
            const toothNumber = $(this).data('tooth');
            $('#modalToothNumber').text(toothNumber);
            $('#tooth_number').val(toothNumber);
            
            // Load existing record
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

        // Handle form submission
        form.on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                tooth_number: $('#tooth_number').val(),
                condition: $('#condition').val(),
                remarks: $('#remarks').val(),
                _token: '<?php echo e(csrf_token()); ?>'
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
                    
                    // Reload page to show updated tooth with new styling
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/patient/teeth-chart.blade.php ENDPATH**/ ?>