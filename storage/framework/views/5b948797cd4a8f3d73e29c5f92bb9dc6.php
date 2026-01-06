<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dental Scheduling System'); ?></title>
    
    <!-- Vite CSS & JS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Heroicons are loaded via blade-heroicons package -->
    <!-- Toggle switch styles are in app.css -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50">
    <?php if(auth()->guard()->check()): ?>
        <!-- Sidebar Overlay for Mobile -->
        <div class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-64 text-white transition-all duration-300 z-50 shadow-2xl overflow-y-auto scrollbar-dental lg:translate-x-0 -translate-x-full <?php echo e(auth()->user()->isAdmin() ? 'bg-gradient-to-b from-gray-800 to-gray-900' : 'bg-gradient-to-b from-dental-teal to-dental-teal-dark'); ?>" id="sidebar">
            <div class="flex items-center justify-between p-4 border-b border-white/10">
                <div class="flex items-center flex-1 min-w-0">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'tooth','class' => 'w-8 h-8 flex-shrink-0 sidebar-tooth-icon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'tooth','class' => 'w-8 h-8 flex-shrink-0 sidebar-tooth-icon']); ?>
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
                    <span class="sidebar-title ml-2 font-bold text-lg whitespace-nowrap overflow-hidden">
                        <?php if(auth()->user()->isAdmin()): ?>
                            Admin Panel
                        <?php else: ?>
                            Dental System
                        <?php endif; ?>
                    </span>
                </div>
                <button class="hidden lg:block text-white hover:bg-white/10 p-2 rounded transition-colors flex-shrink-0" id="toggleSidebar" title="Toggle Sidebar">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'chevron-left','class' => 'w-5 h-5 transition-transform duration-300','id' => 'toggleIcon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-left','class' => 'w-5 h-5 transition-transform duration-300','id' => 'toggleIcon']); ?>
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
                </button>
            </div>

            <ul class="list-none p-0 m-0">
                <?php if(auth()->user()->isAdmin()): ?>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'speedometer2','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'speedometer2','class' => 'w-6 h-6']); ?>
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
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('admin.subdomains.index')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('admin.subdomains.*') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'globe','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'globe','class' => 'w-6 h-6']); ?>
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
                            <span>Subdomains</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('admin.subscriptions.*') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'credit-card','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-6 h-6']); ?>
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
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="#" class="nav-link-dental flex items-center justify-between" 
                           onclick="event.preventDefault(); document.getElementById('analyticsMenu').classList.toggle('hidden');">
                            <div class="flex items-center">
                                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'graph-up','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'graph-up','class' => 'w-6 h-6']); ?>
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
                                <span>Analytics</span>
                            </div>
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'chevron-down','class' => 'w-5 h-5 transition-transform','id' => 'analyticsChevron']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'w-5 h-5 transition-transform','id' => 'analyticsChevron']); ?>
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
                        </a>
                        <div class="<?php echo e(request()->routeIs('admin.reports.*') || request()->routeIs('admin.insights.*') ? '' : 'hidden'); ?>" id="analyticsMenu">
                            <ul class="list-none p-0 m-0">
                                <li class="border-b border-white/10">
                                    <a href="<?php echo e(route('admin.reports.index')); ?>" class="nav-link-dental pl-8 <?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>">
                                        <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'bar-chart','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bar-chart','class' => 'w-6 h-6']); ?>
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
                                        <span>Reports</span>
                                    </a>
                                </li>
                                <li class="border-b border-white/10">
                                    <a href="<?php echo e(route('admin.insights.index')); ?>" class="nav-link-dental pl-8 <?php echo e(request()->routeIs('admin.insights.*') ? 'active' : ''); ?>">
                                        <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'pie-chart','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'pie-chart','class' => 'w-6 h-6']); ?>
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
                                        <span>Insights</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'speedometer2','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'speedometer2','class' => 'w-6 h-6']); ?>
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
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('patients.index')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('patients.*') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'people','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'people','class' => 'w-6 h-6']); ?>
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
                            <span>Patients</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('appointments.index')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('appointments.*') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'calendar-check','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar-check','class' => 'w-6 h-6']); ?>
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
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="border-b border-white/10">
                        <a href="<?php echo e(route('calendar.index')); ?>" class="nav-link-dental <?php echo e(request()->routeIs('calendar.*') ? 'active' : ''); ?>">
                            <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'calendar3','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar3','class' => 'w-6 h-6']); ?>
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
                            <span>Calendar</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="p-4 border-t border-white/10 mt-auto">
                <div class="flex items-center mb-3">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'person-circle','class' => 'w-8 h-8 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'person-circle','class' => 'w-8 h-8 flex-shrink-0']); ?>
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
                    <div class="ml-2 user-info-text flex-1 min-w-0">
                        <div class="text-sm font-bold truncate"><?php echo e(auth()->user()->name); ?></div>
                        <div class="text-xs text-white/70 truncate"><?php echo e(ucfirst(auth()->user()->role)); ?></div>
                    </div>
                </div>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full bg-white/10 hover:bg-white/20 text-white font-semibold py-2 px-4 rounded-lg transition-colors border border-white/20 flex items-center justify-center">
                        <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'box-arrow-right','class' => 'w-5 h-5 mr-2 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'box-arrow-right','class' => 'w-5 h-5 mr-2 flex-shrink-0']); ?>
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
                        <span class="logout-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="ml-0 lg:ml-64 transition-all duration-300 min-h-screen" id="mainContent">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm sticky top-0 z-30 flex justify-between items-center p-4">
                <div class="flex items-center">
                    <span class="text-gray-600 hidden md:block">
                        <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'calendar3','class' => 'w-5 h-5 mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'calendar3','class' => 'w-5 h-5 mr-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?><?php echo e(now()->format('F d, Y')); ?>

                    </span>
                </div>
                <button class="lg:hidden text-dental-teal text-2xl p-2 hover:bg-gray-100 rounded transition-colors" id="mobileToggleSidebar">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'list','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'list','class' => 'w-6 h-6']); ?>
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
                </button>
            </div>

            <!-- Content Area -->
            <div class="p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Public Layout (No Sidebar) -->
        <main class="w-full <?php if(request()->routeIs('welcome') || request()->is('/')): ?> pt-0 pb-4 <?php else: ?> py-4 <?php endif; ?>">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    <?php endif; ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <script>
        // Global SweetAlert configuration with Tailwind colors
        const Swal = window.Swal;
        
        // Configure SweetAlert2 with Tailwind-compatible dental theme
        Swal.mixin({
            customClass: {
                confirmButton: 'btn-dental',
                cancelButton: 'btn-dental-outline',
                popup: 'rounded-2xl',
                title: 'text-gray-800',
                htmlContainer: 'text-gray-600'
            },
            buttonsStyling: false,
            confirmButtonColor: '#20b2aa',
            cancelButtonColor: '#6c757d'
        });
        
        // Show success message from session
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo e(session('success')); ?>',
                confirmButtonColor: '#20b2aa',
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'btn-dental'
                },
                buttonsStyling: false
            });
        <?php endif; ?>

        // Show error message from session
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo e(session('error')); ?>',
                confirmButtonColor: '#dc3545',
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg'
                },
                buttonsStyling: false
            });
        <?php endif; ?>

        // Show validation errors
        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul class="text-left list-disc pl-5 text-gray-700"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>',
                confirmButtonColor: '#dc3545',
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg'
                },
                buttonsStyling: false
            });
        <?php endif; ?>

        // Sidebar Toggle Functionality
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const mainContent = $('#mainContent');
            const toggleBtn = $('#toggleSidebar');
            const toggleIcon = $('#toggleIcon');
            const mobileToggle = $('#mobileToggleSidebar');
            const sidebarOverlay = $('#sidebarOverlay');
            const analyticsMenu = $('#analyticsMenu');
            const analyticsChevron = $('#analyticsChevron');
            let isCollapsed = false;

            // Desktop toggle
            toggleBtn.on('click', function() {
                isCollapsed = !isCollapsed;
                if (isCollapsed) {
                    sidebar.addClass('w-16').removeClass('w-64');
                    mainContent.addClass('lg:ml-16').removeClass('lg:ml-64');
                    // Rotate icon to point right when collapsed
                    toggleIcon.css('transform', 'rotate(180deg)');
                    // Hide text elements
                    $('.sidebar-title').hide();
                    $('.nav-link-dental span').hide();
                    $('.user-info-text').hide();
                    $('.logout-text').hide();
                    // Hide tooth icon, center the chevron button
                    $('.sidebar-tooth-icon').hide();
                    toggleBtn.addClass('mx-auto').removeClass('flex-shrink-0');
                } else {
                    sidebar.addClass('w-64').removeClass('w-16');
                    mainContent.addClass('lg:ml-64').removeClass('lg:ml-16');
                    // Reset icon to point left when expanded
                    toggleIcon.css('transform', 'rotate(0deg)');
                    // Show text elements
                    $('.sidebar-title').show();
                    $('.nav-link-dental span').show();
                    $('.user-info-text').show();
                    $('.logout-text').show();
                    // Show tooth icon, restore chevron position
                    $('.sidebar-tooth-icon').show();
                    toggleBtn.removeClass('mx-auto').addClass('flex-shrink-0');
                }
            });

            // Mobile toggle
            mobileToggle.on('click', function() {
                sidebar.toggleClass('-translate-x-full');
                sidebarOverlay.toggleClass('hidden');
            });

            // Close sidebar when overlay is clicked (mobile)
            sidebarOverlay.on('click', function() {
                sidebar.addClass('-translate-x-full');
                sidebarOverlay.addClass('hidden');
            });

            // Analytics menu toggle (scope to analytics link only)
            $('#analyticsMenu').parent().find('a[href="#"]').on('click', function(e) {
                e.preventDefault();
                analyticsMenu.toggleClass('hidden');
                analyticsChevron.toggleClass('rotate-180');
            });

            // Handle window resize
            $(window).on('resize', function() {
                const isMobileNow = window.innerWidth < 1024;
                if (isMobileNow) {
                    sidebar.addClass('-translate-x-full');
                    sidebarOverlay.addClass('hidden');
                    mainContent.removeClass('lg:ml-16 lg:ml-64').addClass('ml-0');
                } else {
                    sidebar.removeClass('-translate-x-full');
                    sidebarOverlay.addClass('hidden');
                    if (isCollapsed) {
                        mainContent.addClass('lg:ml-16').removeClass('lg:ml-64');
                    } else {
                        mainContent.addClass('lg:ml-64').removeClass('lg:ml-16');
                    }
                }
            });
        });

        // Modal Functions
        window.openModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        };

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.id && e.target.id.includes('modal')) {
                window.closeModal(e.target.id);
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed.flex[id*="modal"]');
                openModals.forEach(modal => {
                    window.closeModal(modal.id);
                });
            }
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH H:\Github\dental_schedule\resources\views/layouts/app.blade.php ENDPATH**/ ?>