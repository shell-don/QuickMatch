<?php $__env->startSection('title', 'Actualités'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">
                        <?php echo e($item->category ?? 'Actualité'); ?>

                    </span>
                    <span class="text-slate-400 text-sm">
                        <i class="bi bi-calendar3 mr-1"></i><?php echo e($item->published_at?->format('d/m/Y')); ?>

                    </span>
                    <span class="text-slate-400 text-sm">
                        <i class="bi bi-source mr-1"></i><?php echo e($item->source); ?>

                    </span>
                </div>
                
                <h2 class="text-xl font-bold text-slate-800 mb-3"><?php echo e($item->title); ?></h2>
                
                <?php if($item->ai_summary): ?>
                    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-4 rounded-r-lg">
                        <p class="text-sm text-indigo-800">
                            <i class="bi bi-stars text-indigo-600 mr-1"></i>
                            <strong>Résumé IA:</strong> <?php echo e($item->ai_summary); ?>

                        </p>
                    </div>
                <?php elseif($item->summary): ?>
                    <p class="text-slate-500 mb-4"><?php echo e($item->summary); ?></p>
                <?php endif; ?>
                
                <a href="<?php echo e(route('news.show', $item)); ?>" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                    Lire la suite <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
            <i class="bi bi-newspaper text-slate-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucune actualité</h3>
            <p class="text-slate-500">Les nouvelles arriveront bientôt.</p>
        </div>
    <?php endif; ?>

    <?php if($news->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($news->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/news/index.blade.php ENDPATH**/ ?>