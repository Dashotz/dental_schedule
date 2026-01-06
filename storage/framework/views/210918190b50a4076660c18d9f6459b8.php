<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'editSubdomainModal','title' => 'Edit Subdomain','size' => 'xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'editSubdomainModal','title' => 'Edit Subdomain','size' => 'xl']); ?>
    <form id="editSubdomainForm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="subdomain_id" id="edit_subdomain_id">
        <div id="editSubdomainModalBody" class="p-6">
            <!-- Content will be loaded via AJAX -->
        </div>
    </form>
    
     <?php $__env->slot('footer', null, []); ?> 
        <div class="flex items-center justify-between w-full">
            <button type="button" class="btn-dental-outline" id="backToViewSubdomainBtn">
                <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'arrow-left','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Back
            </button>
            <div class="flex gap-2">
                <button type="button" class="btn-dental-outline" onclick="closeModal('editSubdomainModal')">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'x-circle','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x-circle','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Cancel
                </button>
                <button type="submit" form="editSubdomainForm" class="btn-dental">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'check-circle','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Update Subdomain
                </button>
            </div>
        </div>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

<?php /**PATH H:\Github\dental_schedule\resources\views/admin/subdomains/partials/edit-modal.blade.php ENDPATH**/ ?>