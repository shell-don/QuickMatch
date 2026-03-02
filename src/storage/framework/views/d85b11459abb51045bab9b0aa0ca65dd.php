<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'placeholder' => 'Sélectionner...',
    'options' => [],
    'selected' => null,
    'error' => null,
    'required' => false,
    'multiple' => false,
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
    'placeholder' => 'Sélectionner...',
    'options' => [],
    'selected' => null,
    'error' => null,
    'required' => false,
    'multiple' => false,
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
    
    <select
        name="<?php echo e($name); ?><?php echo e($multiple ? '[]' : ''); ?>"
        id="<?php echo e($name); ?>"
        <?php echo e($multiple ? 'multiple' : ''); ?>

        <?php echo e($required ? 'required' : ''); ?>

        class="block w-full rounded-md border-gray-300 shadow-sm transition-colors duration-200
            focus:border-indigo-500 focus:ring-indigo-500 focus:ring-2 focus:ring-offset-2
            <?php echo e($error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''); ?>

            px-4 py-2"
    >
        <?php if($placeholder && !$multiple): ?>
            <option value=""><?php echo e($placeholder); ?></option>
        <?php endif; ?>
        
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_array($label)): ?>
                <optgroup label="<?php echo e($value); ?>">
                    <?php $__currentLoopData = $label; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e(in_array($v, old($name, $multiple ? ($selected ?? []) : [$selected])) ? 'selected' : ''); ?>>
                            <?php echo e($l); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </optgroup>
            <?php else: ?>
                <option value="<?php echo e($value); ?>" <?php echo e(in_array($value, old($name, $multiple ? ($selected ?? []) : [$selected])) ? 'selected' : ''); ?>>
                    <?php echo e($label); ?>

                </option>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    
    <?php if($error): ?>
        <p class="text-sm text-red-600"><?php echo e($error); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/components/ui/select.blade.php ENDPATH**/ ?>