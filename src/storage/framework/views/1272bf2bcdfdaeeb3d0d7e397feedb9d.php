<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Auth'); ?> - SmartIntern</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="<?php echo e(route('home')); ?>" class="text-xl font-bold text-slate-800">
                        <i class="text-indigo-600 bi bi-briefcase-fill mr-2"></i><?php echo e(config('app.name')); ?>

                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="text-slate-600 hover:text-slate-900">
                            Dashboard
                        </a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-slate-500 hover:text-slate-700">
                                Déconnexion
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-slate-600 hover:text-slate-900">
                            Connexion
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <?php echo $__env->yieldContent('auth-content'); ?>
        </div>
    </main>
</body>
</html>
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/layouts/auth.blade.php ENDPATH**/ ?>