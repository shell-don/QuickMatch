<?php $__env->startSection('title', $news->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <?php if (isset($component)) { $__componentOriginal38be84e5f9bec265c733edbdb3a68089 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal38be84e5f9bec265c733edbdb3a68089 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.breadcrumbs','data' => ['items' => [
            ['label' => 'Actualités', 'url' => route('news.index')],
            ['label' => Str::limit($news->title, 40)]
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Actualités', 'url' => route('news.index')],
            ['label' => Str::limit($news->title, 40)]
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

<div class="max-w-4xl mx-auto px-4 py-8">
    <article class="bg-white rounded-xl shadow-sm overflow-hidden">
        <?php if($news->image_url): ?>
            <img src="<?php echo e($news->image_url); ?>" alt="<?php echo e($news->title); ?>" class="w-full h-64 object-cover">
        <?php else: ?>
            <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                <i class="bi bi-newspaper text-6xl text-white/50"></i>
            </div>
        <?php endif; ?>
        
        <div class="p-8">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">
                    <?php echo e($news->category ?? 'Actualité'); ?>

                </span>
                <span class="text-slate-400 text-sm">
                    <i class="bi bi-calendar3 mr-1"></i><?php echo e($news->published_at?->format('d/m/Y')); ?>

                </span>
                <span class="text-slate-400 text-sm">
                    <i class="bi bi-source mr-1"></i><?php echo e($news->source); ?>

                </span>
            </div>
            
            <h1 class="text-2xl font-bold text-slate-800 mb-6"><?php echo e($news->title); ?></h1>
            
            <?php if($news->ai_summary): ?>
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6 rounded-r-lg">
                    <p class="text-indigo-800">
                        <i class="bi bi-stars text-indigo-600 mr-2"></i>
                        <strong>Résumé IA:</strong> <?php echo e($news->ai_summary); ?>

                    </p>
                </div>
            <?php endif; ?>
            
            <?php if($news->summary): ?>
                <p class="text-lg text-slate-600 mb-6"><?php echo e($news->summary); ?></p>
            <?php endif; ?>
            
            <?php if($news->content): ?>
                <div class="prose prose-slate max-w-none">
                    <?php echo nl2br(e($news->content)); ?>

                </div>
            <?php endif; ?>
            
            <?php if($news->url): ?>
                <div class="mt-8 pt-6 border-t border-slate-200">
                    <a href="<?php echo e($news->url); ?>" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="bi bi-box-arrow-up-right"></i> Lire l'article complet
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </article>
    
    <div class="mt-6">
        <a href="<?php echo e(route('news.index')); ?>" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600">
            <i class="bi bi-arrow-left"></i> Retour aux actualités
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/news/show.blade.php ENDPATH**/ ?>