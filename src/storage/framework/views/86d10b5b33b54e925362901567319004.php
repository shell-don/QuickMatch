<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
    'home' => true,
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
    'items' => [],
    'home' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(count($items) > 0): ?>
<nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
    <?php if($home): ?>
        <a href="<?php echo e(route('home')); ?>" class="text-slate-400 hover:text-indigo-600 transition-colors">
            <i class="bi bi-house-door"></i>
        </a>
        <span class="text-slate-300">/</span>
    <?php endif; ?>
    
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($index > 0): ?>
            <span class="text-slate-300">/</span>
        <?php endif; ?>
        
        <?php if(isset($item['url']) && $index < count($items) - 1): ?>
            <a href="<?php echo e($item['url']); ?>" class="text-slate-400 hover:text-indigo-600 transition-colors">
                <?php echo e($item['label']); ?>

            </a>
        <?php else: ?>
            <span class="text-slate-800 font-medium"><?php echo e($item['label']); ?></span>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php endif; ?>
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/components/layout/breadcrumbs.blade.php ENDPATH**/ ?>