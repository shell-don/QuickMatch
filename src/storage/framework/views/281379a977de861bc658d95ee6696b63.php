<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'SmartIntern'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-gradient-to-r from-slate-900 to-slate-950 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 text-white font-bold text-xl">
                    <i class="text-indigo-400 bi bi-briefcase-fill"></i>
                    SmartIntern
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="<?php echo e(route('offers.index')); ?>" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition <?php echo e(request()->routeIs('offers.index') ? 'bg-white/10 text-white' : ''); ?>">
                        <i class="bi bi-search mr-1"></i> Offres
                    </a>
                    <a href="<?php echo e(route('professions.index')); ?>" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition <?php echo e(request()->routeIs('professions.index') ? 'bg-white/10 text-white' : ''); ?>">
                        <i class="bi bi-briefcase mr-1"></i> Métiers
                    </a>
                    <a href="<?php echo e(route('formations.index')); ?>" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition <?php echo e(request()->routeIs('formations.index') ? 'bg-white/10 text-white' : ''); ?>">
                        <i class="bi bi-mortarboard mr-1"></i> Formations
                    </a>
                    <a href="<?php echo e(route('chatbot.index')); ?>" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition <?php echo e(request()->routeIs('chatbot.index') ? 'bg-white/10 text-white' : ''); ?>">
                        <i class="bi bi-robot mr-1"></i> Assistant
                    </a>
                    <a href="<?php echo e(route('news.index')); ?>" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition <?php echo e(request()->routeIs('news.index') ? 'bg-white/10 text-white' : ''); ?>">
                        <i class="bi bi-newspaper mr-1"></i> Actualités
                    </a>
                </div>

                <!-- Auth Section -->
                <div class="hidden md:flex items-center gap-3">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('applications.index')); ?>" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition">
                            <i class="bi bi-envelope mr-1"></i> Candidatures
                        </a>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-2 text-slate-300 hover:text-white">
                            <i class="bi bi-person-circle"></i>
                            <?php echo e(auth()->user()->name); ?>

                        </a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="ml-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-slate-300 hover:text-white px-2">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-slate-300 hover:text-white px-3 py-2">Connexion</a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with Sidebar -->
    <div class="flex pt-16 h-screen overflow-hidden">
        <?php if(auth()->guard()->check()): ?>
            <?php if(request()->routeIs('dashboard', 'manager.dashboard', 'applications.*', 'profile.edit')): ?>
                <?php
                    $role = 'user';
                    if (auth()->user()->hasRole('admin')) $role = 'admin';
                    elseif (auth()->user()->hasRole('manager')) $role = 'manager';
                    elseif (auth()->user()->hasRole('user') && !auth()->user()->is_company) $role = 'étudiant';
                ?>
                <?php if (isset($component)) { $__componentOriginal3623d0faebbae10085f2828f046806b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3623d0faebbae10085f2828f046806b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.sidebar','data' => ['role' => $role]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['role' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($role)]); ?>
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
            <?php endif; ?>
        <?php endif; ?>

        <div class="flex-1 overflow-y-auto bg-slate-50">
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
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/layouts/dashboard.blade.php ENDPATH**/ ?>