<?php $__env->startSection('title', $offer->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <?php if (isset($component)) { $__componentOriginal38be84e5f9bec265c733edbdb3a68089 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38be84e5f9bec265c733edbdb3a68089 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.breadcrumbs','data' => ['items' => [
            ['label' => 'Offres', 'url' => route('offers.index')],
            ['label' => Str::limit($offer->title, 40)]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Offres', 'url' => route('offers.index')],
            ['label' => Str::limit($offer->title, 40)]
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
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium mb-2 <?php echo e($offer->type == 'alternance' ? 'bg-green-100 text-green-700' : ($offer->type == 'stage' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700')); ?>">
                            <?php echo e(ucfirst($offer->type)); ?>

                        </span>
                        <h1 class="text-2xl font-bold text-slate-800"><?php echo e($offer->title); ?></h1>
                    </div>
                    <?php if(auth()->guard()->check()): ?>
                        <form action="<?php echo e(route('applications.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="offer_id" value="<?php echo e($offer->id); ?>">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center gap-2">
                                <i class="bi bi-send"></i> Postuler
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-indigo-600 hover:text-white transition flex items-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i> Connectez-vous pour postuler
                        </a>
                    <?php endif; ?>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="mb-2"><i class="bi bi-building mr-2 text-indigo-600"></i><strong>Entreprise:</strong> <?php echo e($offer->company?->name); ?></p>
                        <p class="mb-2"><i class="bi bi-geo-alt mr-2 text-indigo-600"></i><strong>Lieu:</strong> <?php echo e($offer->region?->name ?? 'Non spécifié'); ?></p>
                        <?php if($offer->is_remote): ?>
                            <p class="mb-2"><i class="bi bi-house mr-2 text-green-600"></i><strong>Remote:</strong> Oui</p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if($offer->salary_min || $offer->salary_max): ?>
                            <p class="mb-2"><i class="bi bi-currency-euro mr-2 text-indigo-600"></i><strong>Salaire:</strong> 
                                <?php echo e($offer->salary_min ? $offer->salary_min . '€' : ''); ?> 
                                <?php echo e($offer->salary_min && $offer->salary_max ? '-' : ''); ?>

                                <?php echo e($offer->salary_max ? $offer->salary_max . '€' : ''); ?>

                            </p>
                        <?php endif; ?>
                        <?php if($offer->duration): ?>
                            <p class="mb-2"><i class="bi bi-clock mr-2 text-indigo-600"></i><strong>Durée:</strong> <?php echo e($offer->duration); ?></p>
                        <?php endif; ?>
                        <?php if($offer->start_date): ?>
                            <p class="mb-2"><i class="bi bi-calendar mr-2 text-indigo-600"></i><strong>Début:</strong> <?php echo e($offer->start_date->format('d/m/Y')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                <p class="text-slate-600 mb-6 whitespace-pre-line"><?php echo e($offer->description); ?></p>

                <?php if($offer->requirements): ?>
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Profil recherché</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line"><?php echo e($offer->requirements); ?></p>
                <?php endif; ?>

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php $__empty_1 = true; $__currentLoopData = $offer->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm"><?php echo e($skill->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span class="text-slate-400">Aucune compétence spécifiée</span>
                    <?php endif; ?>
                </div>

                <?php if($offer->source_url): ?>
                    <a href="<?php echo e($offer->source_url); ?>" target="_blank" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 transition">
                        <i class="bi bi-box-arrow-up-right"></i> Voir l'offre originale
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-slate-800 mb-4"><?php echo e($offer->company?->name); ?></h3>
                <?php if($offer->company?->industry): ?>
                    <p class="text-slate-500 text-sm mb-2"><i class="bi bi-tag mr-2"></i><?php echo e($offer->company->industry); ?></p>
                <?php endif; ?>
                <?php if($offer->company?->size): ?>
                    <p class="text-slate-500 text-sm mb-2"><i class="bi bi-people mr-2"></i><?php echo e($offer->company->size); ?></p>
                <?php endif; ?>
                <?php if($offer->company?->region): ?>
                    <p class="text-slate-500 text-sm"><i class="bi bi-geo-alt mr-2"></i><?php echo e($offer->company->region->name); ?></p>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-semibold text-slate-800 mb-3">Autres offres</h4>
                <?php $__empty_1 = true; $__currentLoopData = $offer->company?->offers->where('id', '!=', $offer->id)->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $otherOffer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('offers.show', $otherOffer)); ?>" class="block py-2 border-b border-slate-100 last:border-0 hover:text-indigo-600 transition">
                        <p class="text-sm font-medium text-slate-700 line-clamp-2"><?php echo e($otherOffer->title); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e($otherOffer->region?->name); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-400 text-sm">Aucune autre offre</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/offers/show.blade.php ENDPATH**/ ?>