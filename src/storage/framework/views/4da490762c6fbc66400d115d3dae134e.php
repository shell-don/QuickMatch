<?php $__env->startSection('title', $formation->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <?php if (isset($component)) { $__componentOriginal38be84e5f9bec265c733edbdb3a68089 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38be84e5f9bec265c733edbdb3a68089 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.breadcrumbs','data' => ['items' => [
            ['label' => 'Formations', 'url' => route('formations.index')],
            ['label' => Str::limit($formation->title, 40)]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Formations', 'url' => route('formations.index')],
            ['label' => Str::limit($formation->title, 40)]
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
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($formation->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700'); ?>">
                        <?php echo e(ucfirst($formation->type)); ?>

                    </span>
                    <?php if($formation->level): ?>
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs">
                            <?php echo e($formation->level); ?>

                        </span>
                    <?php endif; ?>
                </div>

                <h1 class="text-2xl font-bold text-slate-800 mb-6"><?php echo e($formation->title); ?></h1>
                
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="mb-2"><i class="bi bi-school mr-2 text-indigo-600"></i><strong>École:</strong> <?php echo e($formation->school ?? 'Non spécifiée'); ?></p>
                        <p class="mb-2"><i class="bi bi-geo-alt mr-2 text-indigo-600"></i><strong>Ville:</strong> <?php echo e($formation->city ?? 'Non spécifiée'); ?></p>
                    </div>
                    <div>
                        <?php if($formation->duration): ?>
                            <p class="mb-2"><i class="bi bi-clock mr-2 text-indigo-600"></i><strong>Durée:</strong> <?php echo e($formation->duration); ?></p>
                        <?php endif; ?>
                        <?php if($formation->region): ?>
                            <p class="mb-2"><i class="bi bi-map mr-2 text-indigo-600"></i><strong>Région:</strong> <?php echo e($formation->region->name); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($formation->description): ?>
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line"><?php echo e($formation->description); ?></p>
                <?php endif; ?>

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php $__empty_1 = true; $__currentLoopData = $formation->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm"><?php echo e($skill->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span class="text-slate-400">Aucune compétence spécifiée</span>
                    <?php endif; ?>
                </div>

                <?php if($formation->url): ?>
                    <a href="<?php echo e($formation->url); ?>" target="_blank" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition">
                        <i class="bi bi-box-arrow-up-right"></i> Voir le site
                    </a>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Métiers associés
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $formation->professions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition">
                            <div>
                                <p class="font-medium text-slate-800"><?php echo e($profession->name); ?></p>
                                <?php if($profession->rome_code): ?>
                                    <p class="text-slate-400 text-sm">Code ROME: <?php echo e($profession->rome_code); ?></p>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('professions.show', $profession)); ?>" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition text-sm">
                                Voir
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-6 text-center text-slate-400">
                            Aucun métier associé
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/formations/show.blade.php ENDPATH**/ ?>