<?php $__env->startSection('title', 'Connexion'); ?>

<?php $__env->startSection('auth-content'); ?>
<div class="bg-white rounded-lg shadow p-8">
    <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Connexion</h1>
    
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
            <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                   type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> 
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Mot de passe</label>
            <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                   type="password" id="password" name="password" required>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> 
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Se connecter
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            <a href="<?php echo e(route('password.request')); ?>" class="text-indigo-600 hover:text-indigo-900">Mot de passe oublié ?</a>
        </p>
        <p class="text-sm text-gray-600 mt-2">
            Pas de compte ? <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:text-indigo-900">S'inscrire</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/auth/login.blade.php ENDPATH**/ ?>