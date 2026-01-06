<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'showClose' => true,
    'footer' => null
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'showClose' => true,
    'footer' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-7xl'
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
?>

<div class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center" id="<?php echo e($id); ?>">
    <div class="bg-white rounded-2xl shadow-2xl <?php echo e($sizeClass); ?> w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="card-dental-header flex justify-between items-center">
            <?php if($title): ?>
                <h5 class="text-lg font-semibold"><?php echo e($title); ?></h5>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            <?php if($showClose): ?>
                <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('<?php echo e($id); ?>')">
                    &times;
                </button>
            <?php endif; ?>
        </div>
        <div class="p-6">
            <?php echo e($slot); ?>

        </div>
        <?php if(isset($footer)): ?>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                <?php echo e($footer); ?>

            </div>
        <?php endif; ?>
    </div>
</div>


<?php /**PATH H:\Github\dental_schedule\resources\views/components/modal.blade.php ENDPATH**/ ?>