<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'error' => null,
    'required' => false,
    'disabled' => false,
    'help' => null,
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
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'error' => null,
    'required' => false,
    'disabled' => false,
    'help' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="space-y-1">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="block text-sm font-medium text-gray-700">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-red-500">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <input
        type="<?php echo e($type); ?>"
        name="<?php echo e($name); ?>"
        id="<?php echo e($name); ?>"
        value="<?php echo e(old($name, $value)); ?>"
        placeholder="<?php echo e($placeholder); ?>"
        <?php echo e($required ? 'required' : ''); ?>

        <?php echo e($disabled ? 'disabled' : ''); ?>

        class="block w-full rounded-md border-gray-300 shadow-sm transition-colors duration-200
            focus:border-indigo-500 focus:ring-indigo-500 focus:ring-2 focus:ring-offset-2
            disabled:bg-gray-100 disabled:cursor-not-allowed
            <?php echo e($error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''); ?>

            px-4 py-2"
    />
    
    <?php if($error): ?>
        <p class="text-sm text-red-600"><?php echo e($error); ?></p>
    <?php elseif($help): ?>
        <p class="text-sm text-gray-500"><?php echo e($help); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/components/ui/input.blade.php ENDPATH**/ ?>