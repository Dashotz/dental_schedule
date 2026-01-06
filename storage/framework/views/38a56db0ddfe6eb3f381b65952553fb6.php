<?php $__env->startSection('title', 'Appointments'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check"></i> Appointments</h2>
    <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Appointment
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($appointment->appointment_date->format('M d, Y')); ?></strong><br>
                                <small class="text-muted"><?php echo e($appointment->appointment_date->format('g:i A')); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo e(route('patients.show', $appointment->patient)); ?>">
                                    <?php echo e($appointment->patient->first_name); ?> <?php echo e($appointment->patient->last_name); ?>

                                </a>
                            </td>
                            <td>
                                <?php echo e($appointment->doctor ? $appointment->doctor->name : 'Unassigned'); ?>

                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e(ucfirst($appointment->type)); ?></span>
                            </td>
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
                            <td><?php echo e($appointment->duration ?? 30); ?> min</td>
                            <td>
                                <a href="<?php echo e(route('appointments.show', $appointment)); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('appointments.edit', $appointment)); ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('appointments.destroy', $appointment)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($appointments->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/appointment/index.blade.php ENDPATH**/ ?>