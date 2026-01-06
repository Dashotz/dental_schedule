<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'speedometer2','class' => 'w-8 h-8 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'speedometer2','class' => 'w-8 h-8 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Admin Dashboard
    </h2>
    <p class="text-gray-600 mt-2">Welcome back, <?php echo e(auth()->user()->name); ?>!</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-dental bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Total Subdomains</h6>
                    <h3 class="text-3xl font-bold mb-0"><?php echo e($totalSubdomains); ?></h3>
                </div>
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'globe','class' => 'w-12 h-12 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'globe','class' => 'w-12 h-12 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Active Subdomains</h6>
                    <h3 class="text-3xl font-bold mb-0"><?php echo e($activeSubdomains); ?></h3>
                </div>
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'check-circle','class' => 'w-12 h-12 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'w-12 h-12 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Active Subscriptions</h6>
                    <h3 class="text-3xl font-bold mb-0"><?php echo e($activeSubscriptions); ?></h3>
                </div>
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'credit-card','class' => 'w-12 h-12 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-12 h-12 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-red-500 to-red-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Expiring Soon</h6>
                    <h3 class="text-3xl font-bold mb-0"><?php echo e($expiringSubscriptions); ?></h3>
                </div>
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'exclamation-triangle','class' => 'w-12 h-12 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'exclamation-triangle','class' => 'w-12 h-12 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="card-dental bg-gradient-to-br from-cyan-500 to-cyan-600 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Total Revenue</h6>
            <h3 class="text-3xl font-bold mb-0">$<?php echo e(number_format($totalRevenue, 2)); ?></h3>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-gray-600 to-gray-700 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Monthly Revenue</h6>
            <h3 class="text-3xl font-bold mb-0">$<?php echo e(number_format($monthlyRevenue, 2)); ?></h3>
        </div>
    </div>
</div>

<!-- Recent Subdomains -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card-dental">
        <div class="card-dental-header">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'globe','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'globe','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Recent Subdomains
            </h5>
        </div>
        <div class="p-6">
            <?php if($recentSubdomains->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdomain</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $recentSubdomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?php echo e($subdomain->subdomain); ?>

                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($subdomain->name); ?>

                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded text-xs font-medium <?php echo e($subdomain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($subdomain->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No subdomains yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Expiring Subscriptions -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'exclamation-triangle','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'exclamation-triangle','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Subscriptions Expiring Soon
            </h5>
        </div>
        <div class="p-6">
            <?php if($expiringSoon->count() > 0): ?>
                <div class="space-y-2">
                    <?php $__currentLoopData = $expiringSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="font-semibold text-gray-800 mb-1"><?php echo e($subdomain->name); ?></h6>
                                    <small class="text-gray-500"><?php echo e($subdomain->subdomain); ?></small>
                                </div>
                                <a href="<?php echo e(route('admin.subdomains.edit', $subdomain)); ?>" class="inline-flex items-center gap-2 btn-dental-outline text-sm py-1.5 px-3">
                                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'pencil','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pencil','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Edit
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No subscriptions expiring soon.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>