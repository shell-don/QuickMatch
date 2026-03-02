<?php $__env->startSection('title', 'Formations'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header with Search -->
<div class="bg-gradient-to-r from-indigo-600 to-indigo-800 py-6">
    <div class="max-w-7xl mx-auto px-4">
        <form action="<?php echo e(route('formations.index')); ?>" method="GET" class="flex gap-3">
            <input 
                type="text" 
                name="search" 
                class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-slate-800" 
                placeholder="Rechercher une formation..."
                value="<?php echo e(request('search')); ?>"
            >
            <!-- Hidden inputs pour préserver les filtres -->
            <?php if(request('type')): ?>
                <input type="hidden" name="type" value="<?php echo e(request('type')); ?>">
            <?php endif; ?>
            <?php if(request('level')): ?>
                <input type="hidden" name="level" value="<?php echo e(request('level')); ?>">
            <?php endif; ?>
            <?php if(request('region_id')): ?>
                <input type="hidden" name="region_id" value="<?php echo e(request('region_id')); ?>">
            <?php endif; ?>
            <?php if(request('skill')): ?>
                <input type="hidden" name="skill" value="<?php echo e(request('skill')); ?>">
            <?php endif; ?>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Filters Horizontal Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="<?php echo e(route('formations.index')); ?>" method="GET" id="filtersForm" class="flex flex-wrap gap-4 items-center">
            <!-- Type -->
            <div class="flex-shrink-0">
                <select name="type" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="">Type</option>
                    <option value="initial" <?php echo e(request('type') == 'initial' ? 'selected' : ''); ?>>Initial</option>
                    <option value="alternance" <?php echo e(request('type') == 'alternance' ? 'selected' : ''); ?>>Alternance</option>
                </select>
            </div>

            <!-- Level -->
            <div class="flex-shrink-0">
                <select name="level" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm">
                    <option value="">Niveau</option>
                    <option value="Bac+2" <?php echo e(request('level') == 'Bac+2' ? 'selected' : ''); ?>>Bac+2</option>
                    <option value="Bac+3" <?php echo e(request('level') == 'Bac+3' ? 'selected' : ''); ?>>Bac+3</option>
                    <option value="Bac+4" <?php echo e(request('level') == 'Bac+4' ? 'selected' : ''); ?>>Bac+4</option>
                    <option value="Bac+5" <?php echo e(request('level') == 'Bac+5' ? 'selected' : ''); ?>>Bac+5</option>
                </select>
            </div>

            <!-- Region -->
            <div class="flex-shrink-0">
                <select name="region_id" class="px-3 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm min-w-[150px]">
                    <option value="">Région</option>
                    <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($region->id); ?>" <?php echo e(request('region_id') == $region->id ? 'selected' : ''); ?>><?php echo e($region->name); ?></option>
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

            <!-- Hidden search input to preserve search query -->
            <?php if(request('search')): ?>
                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
            <?php endif; ?>

            <!-- Appliquer -->
            <div class="flex-shrink-0">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    Appliquer
                </button>
            </div>

            <!-- Reset -->
            <div class="flex-shrink-0">
                <a href="<?php echo e(route('formations.index')); ?>" class="text-sm text-red-600 hover:text-red-800">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Active Filters Tags -->
    <?php if(request('type') || request('level') || request('region_id') || request('skill') || request('search')): ?>
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="text-sm text-slate-500">Filtres actifs :</span>
            <?php if(request('search')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e(request('search')); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['search' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('type')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e(ucfirst(request('type'))); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['type' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('level')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e(request('level')); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['level' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('region_id')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e($regions->find(request('region_id'))?->name); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['region_id' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
            <?php if(request('skill')): ?>
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                    <?php echo e($skills->find(request('skill'))?->name); ?>

                    <a href="<?php echo e(request()->fullUrlWithQuery(['skill' => null])); ?>" class="hover:text-indigo-900">×</a>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Results Counter -->
    <div class="mb-4">
        <p class="text-slate-600"><?php echo e($formations->total()); ?> formation<?php echo e($formations->total() !== 1 ? 's' : ''); ?> trouvée<?php echo e($formations->total() !== 1 ? 's' : ''); ?></p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-5">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($formation->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700'); ?>">
                            <?php echo e(ucfirst($formation->type)); ?>

                        </span>
                        <?php if($formation->level): ?>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs">
                                <?php echo e($formation->level); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2"><?php echo e($formation->title); ?></h3>
                    
                    <div class="space-y-1 text-slate-500 text-sm mb-3">
                        <?php if($formation->school): ?>
                            <p><i class="bi bi-school mr-1"></i><?php echo e($formation->school); ?></p>
                        <?php endif; ?>
                        <?php if($formation->city): ?>
                            <p><i class="bi bi-geo-alt mr-1"></i><?php echo e($formation->city); ?></p>
                        <?php endif; ?>
                        <?php if($formation->duration): ?>
                            <p><i class="bi bi-clock mr-1"></i><?php echo e($formation->duration); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex flex-wrap gap-1 mb-4">
                        <?php $__currentLoopData = $formation->skills->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs"><?php echo e($skill->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <a href="<?php echo e(route('formations.show', $formation)); ?>" class="block w-full text-center py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                        Voir détails <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
                    <i class="bi bi-mortarboard text-slate-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucune formation</h3>
                    <p class="text-slate-500">Aucune formation disponible pour le moment.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($formations->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($formations->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/formations/index.blade.php ENDPATH**/ ?>