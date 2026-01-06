<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'createSubdomainModal','title' => 'Create New Subdomain','size' => 'xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'createSubdomainModal','title' => 'Create New Subdomain','size' => 'xl']); ?>
    <form id="createSubdomainForm">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label for="modal_subdomain" class="form-label flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'globe','class' => 'w-5 h-5 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'globe','class' => 'w-5 h-5 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>Subdomain <span class="text-red-500">*</span>
                </label>
                <div class="flex">
                    <input type="text" 
                           class="input-dental rounded-r-none" 
                           id="modal_subdomain" 
                           name="subdomain" 
                           placeholder="clinic-name" 
                           required>
                    <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg px-4 py-2.5 text-gray-600 flex items-center">.helioho.st</span>
                </div>
                <small class="text-gray-500 block mt-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'info-circle','class' => 'w-4 h-4 inline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info-circle','class' => 'w-4 h-4 inline']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?> Only lowercase letters, numbers, and hyphens allowed.
                </small>
                <div class="text-red-500 text-sm mt-1 hidden" id="subdomain_error"></div>
            </div>

            <div class="md:col-span-2">
                <label for="modal_name" class="form-label flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'building','class' => 'w-5 h-5 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'building','class' => 'w-5 h-5 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>Clinic Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       class="input-dental" 
                       id="modal_name" 
                       name="name" 
                       placeholder="Enter clinic name"
                       required>
                <div class="text-red-500 text-sm mt-1 hidden" id="name_error"></div>
            </div>

            <div class="md:col-span-2">
                <label for="modal_description" class="form-label flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'file-text','class' => 'w-5 h-5 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-5 h-5 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>Description
                </label>
                <textarea class="input-dental" 
                          id="modal_description" 
                          name="description" 
                          rows="3"
                          placeholder="Enter a brief description about the clinic"></textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
            </div>

            <div>
                <label for="modal_email" class="form-label flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'envelope','class' => 'w-5 h-5 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'envelope','class' => 'w-5 h-5 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>Email
                </label>
                <input type="email" 
                       class="input-dental" 
                       id="modal_email" 
                       name="email" 
                       placeholder="clinic@example.com">
                <div class="text-red-500 text-sm mt-1 hidden" id="email_error"></div>
            </div>

            <div>
                <label for="modal_phone" class="form-label flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'telephone','class' => 'w-5 h-5 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'telephone','class' => 'w-5 h-5 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>Phone
                </label>
                <input type="text" 
                       class="input-dental" 
                       id="modal_phone" 
                       name="phone" 
                       placeholder="+1 (555) 123-4567">
                <div class="text-red-500 text-sm mt-1 hidden" id="phone_error"></div>
            </div>

            <div class="md:col-span-2">
                <label for="modal_address" class="form-label flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginal9acdd978a59e943fbf0d4792f9858795 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9acdd978a59e943fbf0d4792f9858795 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dental-icon','data' => ['name' => 'geo-alt','class' => 'w-5 h-5 text-dental-teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dental-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'geo-alt','class' => 'w-5 h-5 text-dental-teal']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $attributes = $__attributesOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__attributesOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9acdd978a59e943fbf0d4792f9858795)): ?>
<?php $component = $__componentOriginal9acdd978a59e943fbf0d4792f9858795; ?>
<?php unset($__componentOriginal9acdd978a59e943fbf0d4792f9858795); ?>
<?php endif; ?>Address
                </label>
                <textarea class="input-dental" 
                          id="modal_address" 
                          name="address" 
                          rows="3"
                          placeholder="Enter clinic address"></textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="address_error"></div>
            </div>
        </div>
    </form>
    
     <?php $__env->slot('footer', null, []); ?> 
        <button type="button" class="btn-dental-outline" onclick="closeModal('createSubdomainModal')">
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
        <button type="submit" form="createSubdomainForm" class="btn-dental">
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
<?php endif; ?> Create Subdomain
        </button>
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

<?php /**PATH H:\Github\dental_schedule\resources\views/admin/subdomains/partials/create-modal.blade.php ENDPATH**/ ?>