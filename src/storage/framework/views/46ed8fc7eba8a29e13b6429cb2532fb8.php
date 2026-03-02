<?php $__env->startSection('title', $profession->name); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <?php if (isset($component)) { $__componentOriginal38be84e5f9bec265c733edbdb3a68089 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38be84e5f9bec265c733edbdb3a68089 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.breadcrumbs','data' => ['items' => [
            ['label' => 'Métiers', 'url' => route('professions.index')],
            ['label' => $profession->name]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Métiers', 'url' => route('professions.index')],
            ['label' => $profession->name]
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal38be84e5f9bec265c733edbdb3a68089)): ?>
<?php $attributes = $__attributesOriginal38be84e5f9bec265c733edbdb3a68089; ?>
<?php unset($__attributesOriginal38be84e5f9bec265c733edbdb3a68089); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal38be84e5f9bec265c733edbdb3a68089)): ?>
<?php $component = $__componentOriginal38be84e5f9bec265c733edbdb3a68089; ?>
<?php unset($__componentOriginal38be84e5f9bec265c733edbdb3a68089); ?>
<?php endif; ?>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-slate-800 mb-2"><?php echo e($profession->name); ?></h1>
                <?php if($profession->rome_code): ?>
                    <p class="text-slate-500 mb-6">Code ROME: <?php echo e($profession->rome_code); ?></p>
                <?php endif; ?>
                
                <?php if($profession->description): ?>
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line"><?php echo e($profession->description); ?></p>
                <?php endif; ?>

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php $__empty_1 = true; $__currentLoopData = $profession->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm"><?php echo e($skill->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span class="text-slate-400">Aucune compétence spécifiée</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Offres associées
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $profession->offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition">
                            <div>
                                <p class="font-medium text-slate-800"><?php echo e($offer->title); ?></p>
                                <p class="text-slate-400 text-sm"><?php echo e($offer->company?->name); ?></p>
                            </div>
                            <a href="<?php echo e(route('offers.show', $offer)); ?>" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition text-sm">
                                Voir
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-6 text-center text-slate-400">
                            Aucune offre disponible
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-mortarboard mr-2"></i>Formations
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $profession->formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 hover:bg-slate-50 transition">
                            <p class="font-medium text-slate-800"><?php echo e($formation->title); ?></p>
                            <p class="text-slate-400 text-sm"><?php echo e($formation->school); ?> - <?php echo e($formation->level); ?></p>
                            <a href="<?php echo e(route('formations.show', $formation)); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm">Voir détails</a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-center text-slate-400">
                            Aucune formation
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/professions/show.blade.php ENDPATH**/ ?>