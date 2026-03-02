<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> - <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-gradient-to-r from-slate-900 to-slate-950 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 text-white font-bold text-xl">
                    <i class="text-indigo-400 bi bi-briefcase-fill"></i>
                    SmartIntern
                </a>

                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('home')); ?>" class="text-slate-300 hover:text-white px-3 py-2">
                        <i class="bi bi-house mr-1"></i>Accueil
                    </a>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-2 text-slate-300 hover:text-white">
                        <i class="bi bi-person-circle"></i>
                        <?php echo e(auth()->user()->name); ?>

                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-slate-300 hover:text-white px-2">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with Sidebar -->
    <div class="flex pt-16 min-h-screen">
        <?php if (isset($component)) { $__componentOriginal3623d0faebbae10085f2828f046806b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3623d0faebbae10085f2828f046806b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.sidebar','data' => ['role' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['role' => 'admin']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3623d0faebbae10085f2828f046806b2)): ?>
<?php $attributes = $__attributesOriginal3623d0faebbae10085f2828f046806b2; ?>
<?php unset($__attributesOriginal3623d0faebbae10085f2828f046806b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3623d0faebbae10085f2828f046806b2)): ?>
<?php $component = $__componentOriginal3623d0faebbae10085f2828f046806b2; ?>
<?php unset($__componentOriginal3623d0faebbae10085f2828f046806b2); ?>
<?php endif; ?>

        <div class="flex-1 p-8">
            <?php if(session('success')): ?>
                <?php if (isset($component)) { $__componentOriginal339c7fedf680433726dbafc2f156956f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal339c7fedf680433726dbafc2f156956f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.toast','data' => ['type' => 'success','message' => session('success')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('success'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal339c7fedf680433726dbafc2f156956f)): ?>
<?php $attributes = $__attributesOriginal339c7fedf680433726dbafc2f156956f; ?>
<?php unset($__attributesOriginal339c7fedf680433726dbafc2f156956f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal339c7fedf680433726dbafc2f156956f)): ?>
<?php $component = $__componentOriginal339c7fedf680433726dbafc2f156956f; ?>
<?php unset($__componentOriginal339c7fedf680433726dbafc2f156956f); ?>
<?php endif; ?>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <?php if (isset($component)) { $__componentOriginal339c7fedf680433726dbafc2f156956f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal339c7fedf680433726dbafc2f156956f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.toast','data' => ['type' => 'error','message' => session('error')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('error'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal339c7fedf680433726dbafc2f156956f)): ?>
<?php $attributes = $__attributesOriginal339c7fedf680433726dbafc2f156956f; ?>
<?php unset($__attributesOriginal339c7fedf680433726dbafc2f156956f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal339c7fedf680433726dbafc2f156956f)): ?>
<?php $component = $__componentOriginal339c7fedf680433726dbafc2f156956f; ?>
<?php unset($__componentOriginal339c7fedf680433726dbafc2f156956f); ?>
<?php endif; ?>
            <?php endif; ?>
            <?php if(session('warning')): ?>
                <?php if (isset($component)) { $__componentOriginal339c7fedf680433726dbafc2f156956f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal339c7fedf680433726dbafc2f156956f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.toast','data' => ['type' => 'warning','message' => session('warning')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'warning','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('warning'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal339c7fedf680433726dbafc2f156956f)): ?>
<?php $attributes = $__attributesOriginal339c7fedf680433726dbafc2f156956f; ?>
<?php unset($__attributesOriginal339c7fedf680433726dbafc2f156956f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal339c7fedf680433726dbafc2f156956f)): ?>
<?php $component = $__componentOriginal339c7fedf680433726dbafc2f156956f; ?>
<?php unset($__componentOriginal339c7fedf680433726dbafc2f156956f); ?>
<?php endif; ?>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/admin/layouts/admin.blade.php ENDPATH**/ ?>