<?php $__env->startSection('title', $company->name); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <?php if (isset($component)) { $__componentOriginal38be84e5f9bec265c733edbdb3a68089 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38be84e5f9bec265c733edbdb3a68089 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.breadcrumbs','data' => ['items' => [
            ['label' => 'Entreprises', 'url' => route('companies.index')],
            ['label' => $company->name]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Entreprises', 'url' => route('companies.index')],
            ['label' => $company->name]
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
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <i class="bi bi-building text-indigo-600 text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800"><?php echo e($company->name); ?></h1>
                        <?php if($company->industry): ?>
                            <p class="text-slate-500"><?php echo e($company->industry); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($company->description): ?>
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">À propos</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line"><?php echo e($company->description); ?></p>
                <?php endif; ?>

                <div class="grid md:grid-cols-3 gap-4">
                    <?php if($company->size): ?>
                        <div>
                            <p class="text-sm text-slate-500 mb-1"><i class="bi bi-people mr-2"></i><strong>Taille:</strong></p>
                            <p class="text-slate-700"><?php echo e($company->size); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($company->region): ?>
                        <div>
                            <p class="text-sm text-slate-500 mb-1"><i class="bi bi-geo-alt mr-2"></i><strong>Localisation:</strong></p>
                            <p class="text-slate-700"><?php echo e($company->region->name); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($company->website): ?>
                        <div>
                            <p class="text-sm text-slate-500 mb-1"><i class="bi bi-globe mr-2"></i><strong>Site web:</strong></p>
                            <a href="<?php echo e($company->website); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 truncate block"><?php echo e($company->website); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Offres (<?php echo e($company->offers->count()); ?>)
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $company->offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition">
                            <div>
                                <p class="font-medium text-slate-800"><?php echo e($offer->title); ?></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($offer->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700'); ?>">
                                        <?php echo e(ucfirst($offer->type)); ?>

                                    </span>
                                    <?php if($offer->region): ?>
                                        <span class="text-slate-400 text-sm"><i class="bi bi-geo-alt mr-1"></i><?php echo e($offer->region->name); ?></span>
                                    <?php endif; ?>
                                </div>
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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/companies/show.blade.php ENDPATH**/ ?>