<?php $__env->startSection('title', 'Offres de stages et alternances'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mt-6 max-w-3xl mx-auto">
            <form action="<?php echo e(route('offers.index')); ?>" method="GET" class="flex gap-3">
                <input 
                    type="text" 
                    name="search" 
                    class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-slate-800" 
                    placeholder="Rechercher une offre, entreprise, compétence..."
                    value="<?php echo e(request('search')); ?>"
                >
                <!-- Hidden inputs pour préserver les filtres -->
                <?php if(request('type')): ?>
                    <input type="hidden" name="type" value="<?php echo e(request('type')); ?>">
                <?php endif; ?>
                <?php if(request('region')): ?>
                    <input type="hidden" name="region" value="<?php echo e(request('region')); ?>">
                <?php endif; ?>
                <?php if(request('profession')): ?>
                    <input type="hidden" name="profession" value="<?php echo e(request('profession')); ?>">
                <?php endif; ?>
                <?php if(request('skill')): ?>
                    <input type="hidden" name="skill" value="<?php echo e(request('skill')); ?>">
                <?php endif; ?>
                <?php if(request('salary_min')): ?>
                    <input type="hidden" name="salary_min" value="<?php echo e(request('salary_min')); ?>">
                <?php endif; ?>
                <?php if(request('salary_max')): ?>
                    <input type="hidden" name="salary_max" value="<?php echo e(request('salary_max')); ?>">
                <?php endif; ?>
                <?php if(request('remote')): ?>
                    <input type="hidden" name="remote" value="<?php echo e(request('remote')); ?>">
                <?php endif; ?>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Filters Horizontal Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="<?php echo e(route('offers.index')); ?>" method="GET" id="filtersForm" class="flex flex-wrap gap-4 items-center">
            <!-- Type -->
            <div class="flex-shrink-0">
                <select name="type" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="">Type</option>
                    <option value="stage" <?php echo e(request('type') == 'stage' ? 'selected' : ''); ?>>Stage</option>
                    <option value="alternance" <?php echo e(request('type') == 'alternance' ? 'selected' : ''); ?>>Alternance</option>
                    <option value="cdi" <?php echo e(request('type') == 'cdi' ? 'selected' : ''); ?>>CDI</option>
                    <option value="cdd" <?php echo e(request('type') == 'cdd' ? 'selected' : ''); ?>>CDD</option>
                    <option value="job" <?php echo e(request('type') == 'job' ? 'selected' : ''); ?>>Emploi</option>
                </select>
            </div>
            
            <!-- Région -->
            <div class="flex-shrink-0">
                <select name="region" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm min-w-[150px]">
                    <option value="">Région</option>
                    <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($region->id); ?>" <?php echo e(request('region') == $region->id ? 'selected' : ''); ?>><?php echo e($region->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Métier -->
            <div class="flex-shrink-0">
                <select name="profession" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm min-w-[150px]">
                    <option value="">Métier</option>
                    <?php $__currentLoopData = $professions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($profession->id); ?>" <?php echo e(request('profession') == $profession->id ? 'selected' : ''); ?>><?php echo e($profession->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Compétences -->
            <div class="flex-shrink-0">
                <select name="skill" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm min-w-[150px]">
                    <option value="">Compétences</option>
                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($skill->id); ?>" <?php echo e(request('skill') == $skill->id ? 'selected' : ''); ?>><?php echo e($skill->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Salaire min -->
            <div class="flex-shrink-0">
                <input 
                    type="number" 
                    name="salary_min" 
                    value="<?php echo e(request('salary_min')); ?>"
                    class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm w-24" 
                    placeholder="Salaire min"
                >
            </div>

            <!-- Salaire max -->
            <div class="flex-shrink-0">
                <input 
                    type="number" 
                    name="salary_max" 
                    value="<?php echo e(request('salary_max')); ?>"
                    class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm w-24" 
                    placeholder="Salaire max"
                >
            </div>

            <!-- Télétravail -->
            <div class="flex-shrink-0">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-700">
                    <input 
                        type="checkbox" 
                        name="remote" 
                        value="1" 
                        <?php echo e(request('remote') == '1' ? 'checked' : ''); ?>

                        class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500"
                    >
                    <span>Remote</span>
                </label>
            </div>

            <!-- Appliquer -->
            <div class="flex-shrink-0">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    Appliquer
                </button>
            </div>

            <!-- Reset -->
            <div class="flex-shrink-0">
                <a href="<?php echo e(route('offers.index')); ?>" class="text-sm text-red-600 hover:text-red-800">
                    Réinitialiser
                </a>
            </div>

            <!-- Hidden search input to preserve search query -->
            <?php if(request('search')): ?>
                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
            <?php endif; ?>
        </form>
    </div>

    <!-- Active Filters Tags -->
    <?php if(request('type') || request('region') || request('skill') || request('remote') || request('profession') || request('salary_min') || request('salary_max')): ?>
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="text-sm text-slate-500">Filtres actifs :</span>
            <?php if(request('type')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e(ucfirst(request('type'))); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['type' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('region')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e($regions->find(request('region'))?->name); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['region' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('skill')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e($skills->find(request('skill'))?->name); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['skill' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('remote')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    Télétravail
                    <a href="<?php echo e(request()->fullUrlWithQuery(['remote' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('profession')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e($professions->find(request('profession'))?->name); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['profession' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('salary_min')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    Min <?php echo e(request('salary_min')); ?>€
                    <a href="<?php echo e(request()->fullUrlWithQuery(['salary_min' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('salary_max')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    Max <?php echo e(request('salary_max')); ?>€
                    <a href="<?php echo e(request()->fullUrlWithQuery(['salary_max' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Offers Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Results Counter -->
        <div class="col-span-full mb-2">
            <p class="text-slate-600"><?php echo e($offers->total()); ?> offre<?php echo e($offers->total() !== 1 ? 's' : ''); ?> trouvée<?php echo e($offers->total() !== 1 ? 's' : ''); ?></p>
        </div>
        
        <?php $__empty_1 = true; $__currentLoopData = $offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($offer->type == 'alternance' ? 'bg-green-100 text-green-700' : ($offer->type == 'stage' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700')); ?>">
                            <?php echo e(ucfirst($offer->type)); ?>

                        </span>
                        <?php if($offer->is_remote): ?>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">Remote</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2"><?php echo e($offer->title); ?></h3>
                    <p class="text-slate-500 text-sm mb-1">
                        <i class="bi bi-building mr-1"></i><?php echo e($offer->company?->name); ?>

                    </p>
                    <p class="text-slate-500 text-sm mb-3">
                        <i class="bi bi-geo-alt mr-1"></i><?php echo e($offer->region?->name ?? 'Non spécifié'); ?>

                    </p>
                    <div class="flex flex-wrap gap-1 mb-4">
                        <?php $__currentLoopData = $offer->skills->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs"><?php echo e($skill->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <a href="<?php echo e(route('offers.show', $offer)); ?>" class="block w-full text-center py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                        Voir détails
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full">
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                    <i class="bi bi-info-circle mr-2"></i>Aucune offre disponible pour le moment.
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if($offers->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($offers->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/offers/index.blade.php ENDPATH**/ ?>