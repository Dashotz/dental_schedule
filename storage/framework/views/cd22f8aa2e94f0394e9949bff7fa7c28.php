<?php $__env->startSection('title', 'Patients'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Patients</h2>
    <div>
        <form method="GET" action="<?php echo e(route('patients.index')); ?>" class="d-inline">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search patients..." value="<?php echo e(request('search')); ?>">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></strong>
                            </td>
                            <td><?php echo e($patient->email ?? 'N/A'); ?></td>
                            <td><?php echo e($patient->phone ?? 'N/A'); ?></td>
                            <td><?php echo e($patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A'); ?></td>
                            <td><?php echo e($patient->gender ? ucfirst($patient->gender) : 'N/A'); ?></td>
                            <td><?php echo e($patient->created_at->format('M d, Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('patients.show', $patient)); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="<?php echo e(route('patients.edit', $patient)); ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">No patients found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($patients->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/patient/index.blade.php ENDPATH**/ ?>