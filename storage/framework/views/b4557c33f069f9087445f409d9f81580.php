<?php $__env->startSection('title', 'Appointment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check"></i> Appointment Details</h2>
    <div>
        <a href="<?php echo e(route('appointments.edit', $appointment)); ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Appointment Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Date & Time:</th>
                        <td>
                            <strong><?php echo e($appointment->appointment_date->format('F d, Y')); ?></strong><br>
                            <small class="text-muted"><?php echo e($appointment->appointment_date->format('g:i A')); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <th>Patient:</th>
                        <td>
                            <a href="<?php echo e(route('patients.show', $appointment->patient)); ?>">
                                <?php echo e($appointment->patient->first_name); ?> <?php echo e($appointment->patient->last_name); ?>

                            </a>
                            <br>
                            <small class="text-muted"><?php echo e($appointment->patient->phone ?? 'N/A'); ?></small>
                        </td>
                    </tr>
                    <tr>
                        <th>Doctor:</th>
                        <td><?php echo e($appointment->doctor ? $appointment->doctor->name : 'Unassigned'); ?></td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><span class="badge bg-info"><?php echo e(ucfirst($appointment->type)); ?></span></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php
                                $statusColors = [
                                    'scheduled' => 'primary',
                                    'confirmed' => 'success',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    'no_show' => 'secondary'
                                ];
                                $color = $statusColors[$appointment->status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?php echo e($color); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $appointment->status))); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Duration:</th>
                        <td><?php echo e($appointment->duration ?? 30); ?> minutes</td>
                    </tr>
                    <?php if($appointment->reason): ?>
                    <tr>
                        <th>Reason:</th>
                        <td><?php echo e($appointment->reason); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($appointment->notes): ?>
                    <tr>
                        <th>Notes:</th>
                        <td><?php echo e($appointment->notes); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Created By:</th>
                        <td><?php echo e($appointment->createdBy ? $appointment->createdBy->name : 'System'); ?></td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td><?php echo e($appointment->created_at->format('F d, Y g:i A')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('patients.show', $appointment->patient)); ?>" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-person"></i> View Patient
                </a>
                <?php if($appointment->status !== 'completed' && $appointment->status !== 'cancelled'): ?>
                    <form action="<?php echo e(route('appointments.destroy', $appointment)); ?>" method="POST" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-circle"></i> Cancel Appointment
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/appointment/show.blade.php ENDPATH**/ ?>