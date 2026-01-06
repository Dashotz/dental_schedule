<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <p class="text-muted">Welcome back, <?php echo e(auth()->user()->name); ?>!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Subdomains</h6>
                        <h3 class="mb-0"><?php echo e($totalSubdomains); ?></h3>
                    </div>
                    <i class="bi bi-globe fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Active Subdomains</h6>
                        <h3 class="mb-0"><?php echo e($activeSubdomains); ?></h3>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Active Subscriptions</h6>
                        <h3 class="mb-0"><?php echo e($activeSubscriptions); ?></h3>
                    </div>
                    <i class="bi bi-credit-card fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Expiring Soon</h6>
                        <h3 class="mb-0"><?php echo e($expiringSubscriptions); ?></h3>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Total Revenue</h6>
                <h3 class="mb-0">$<?php echo e(number_format($totalRevenue, 2)); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Monthly Revenue</h6>
                <h3 class="mb-0">$<?php echo e(number_format($monthlyRevenue, 2)); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Recent Subdomains -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-globe"></i> Recent Subdomains</h5>
            </div>
            <div class="card-body">
                <?php if($recentSubdomains->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subdomain</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentSubdomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($subdomain->subdomain); ?></td>
                                        <td><?php echo e($subdomain->name); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($subdomain->is_active ? 'success' : 'danger'); ?>">
                                                <?php echo e($subdomain->is_active ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.subdomains.show', $subdomain)); ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No subdomains yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Expiring Subscriptions -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Subscriptions Expiring Soon</h5>
            </div>
            <div class="card-body">
                <?php if($expiringSoon->count() > 0): ?>
                    <div class="list-group">
                        <?php $__currentLoopData = $expiringSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($subdomain->name); ?></h6>
                                        <small class="text-muted"><?php echo e($subdomain->subdomain); ?></small>
                                    </div>
                                    <a href="<?php echo e(route('admin.subdomains.show', $subdomain)); ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No subscriptions expiring soon.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>