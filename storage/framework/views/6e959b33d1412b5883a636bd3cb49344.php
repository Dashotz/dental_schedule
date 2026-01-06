<?php $__env->startSection('title', 'Patient Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person"></i> Patient Details</h2>
    <div>
        <a href="<?php echo e(route('patients.edit', $patient)); ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <!-- Patient Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>First Name:</strong>
                        <p><?php echo e($patient->first_name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Last Name:</strong>
                        <p><?php echo e($patient->last_name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date of Birth:</strong>
                        <p><?php echo e($patient->date_of_birth ? $patient->date_of_birth->format('F d, Y') : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Gender:</strong>
                        <p><?php echo e($patient->gender ? ucfirst($patient->gender) : 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p><?php echo e($patient->email ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p><?php echo e($patient->phone ?? 'N/A'); ?></p>
                    </div>
                    <?php if($patient->phone_alt): ?>
                    <div class="col-md-6 mb-3">
                        <strong>Alternate Phone:</strong>
                        <p><?php echo e($patient->phone_alt); ?></p>
                    </div>
                    <?php endif; ?>
                    <div class="col-12 mb-3">
                        <strong>Address:</strong>
                        <p><?php echo e($patient->address ?? 'N/A'); ?></p>
                    </div>
                    <?php if($patient->city || $patient->state || $patient->zip_code): ?>
                    <div class="col-md-4 mb-3">
                        <strong>City:</strong>
                        <p><?php echo e($patient->city ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>State:</strong>
                        <p><?php echo e($patient->state ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Zip Code:</strong>
                        <p><?php echo e($patient->zip_code ?? 'N/A'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Medical Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Medical History:</strong>
                        <p><?php echo e($patient->medical_history ? nl2br(e($patient->medical_history)) : 'None recorded'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Allergies:</strong>
                        <p><?php echo e($patient->allergies ? nl2br(e($patient->allergies)) : 'None recorded'); ?></p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Current Medications:</strong>
                        <p><?php echo e($patient->medications ? nl2br(e($patient->medications)) : 'None recorded'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <?php if($patient->emergency_contact_name || $patient->emergency_contact_phone): ?>
        <div class="card mt-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-telephone"></i> Emergency Contact</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p><?php echo e($patient->emergency_contact_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p><?php echo e($patient->emergency_contact_phone ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Insurance Information -->
        <?php if($patient->insurance_provider || $patient->insurance_policy_number): ?>
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-shield-check"></i> Insurance Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Provider:</strong>
                        <p><?php echo e($patient->insurance_provider ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Policy Number:</strong>
                        <p><?php echo e($patient->insurance_policy_number ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Dental Chart -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-teeth"></i> Dental Chart</h6>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('patients.teeth-chart', $patient)); ?>" class="btn btn-info w-100">
                    <i class="bi bi-grid"></i> View Teeth Chart
                </a>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Recent Appointments</h6>
            </div>
            <div class="card-body">
                <?php if($patient->appointments->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $patient->appointments->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($appointment->appointment_date->format('M d, Y')); ?></h6>
                                        <small class="text-muted"><?php echo e($appointment->appointment_date->format('g:i A')); ?></small>
                                        <br>
                                        <span class="badge bg-<?php echo e($appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'primary')); ?>">
                                            <?php echo e(ucfirst($appointment->status)); ?>

                                        </span>
                                    </div>
                                    <a href="<?php echo e(route('appointments.show', $appointment)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-2">
                        <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-sm btn-outline-primary w-100">
                            View All Appointments
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">No appointments yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/patient/show.blade.php ENDPATH**/ ?>