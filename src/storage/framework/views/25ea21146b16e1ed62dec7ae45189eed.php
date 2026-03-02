<?php $__env->startSection('title', 'Métiers'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Filters Horizontal Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="<?php echo e(route('professions.index')); ?>" method="GET" class="flex gap-3">
            <input 
                type="text" 
                name="search" 
                class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm" 
                placeholder="Rechercher un métier..."
                value="<?php echo e(request('search')); ?>"
            >
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- Active Filters Tags -->
    <?php if(request('search')): ?>
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="text-sm text-slate-500">Filtres actifs :</span>
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                <?php echo e(request('search')); ?>

                <a href="<?php echo e(request()->fullUrlWithQuery(['search' => null])); ?>" class="hover:text-indigo-900">×</a>
            </span>
            <a href="<?php echo e(route('professions.index')); ?>" class="text-sm text-red-600 hover:text-red-800">
                Réinitialiser
            </a>
        </div>
    <?php endif; ?>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $professions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-5">
                    <h3 class="font-semibold text-lg mb-2"><?php echo e($profession->name); ?></h3>
                    
                    <?php if($profession->rome_code): ?>
                        <p class="text-slate-500 text-sm mb-2">Code ROME: <?php echo e($profession->rome_code); ?></p>
                    <?php endif; ?>
                    
                    <?php if($profession->description): ?>
                        <p class="text-slate-500 text-sm mb-3 line-clamp-3"><?php echo e(Str::limit($profession->description, 100)); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap gap-1 mb-4">
                        <?php $__currentLoopData = $profession->skills->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs"><?php echo e($skill->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($profession->skills->count() > 5): ?>
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">+<?php echo e($profession->skills->count() - 5); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?php echo e(route('professions.show', $profession)); ?>" class="block w-full text-center py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                        Voir les formations <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
                    <i class="bi bi-briefcase text-slate-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucun métier</h3>
                    <p class="text-slate-500">Aucun métier disponible pour le moment.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($professions->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($professions->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/professions/index.blade.php ENDPATH**/ ?>