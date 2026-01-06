<?php $__env->startSection('title', 'Welcome - Dental Scheduling System'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8 text-center">
        <div class="py-5">
            <h1 class="display-4 mb-4">
                <i class="bi bi-tooth text-primary"></i> Dental Scheduling System
            </h1>
            <p class="lead mb-4">
                Welcome to our dental clinic management system. 
                To book an appointment, please use the registration link provided by our staff.
            </p>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                <strong>Note:</strong> Patient registration is only available through a valid registration link sent by our clinic staff.
            </div>
            <div class="mt-4">
                    <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary btn-lg me-2">
                        <i class="bi bi-speedometer2"></i> Go to Dashboard
                        </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-lg me-2">
                        <i class="bi bi-box-arrow-in-right"></i> Staff Login
                    </a>
                    <?php endif; ?>
                </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/welcome.blade.php ENDPATH**/ ?>