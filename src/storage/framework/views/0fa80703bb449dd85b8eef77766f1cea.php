<?php $__env->startSection('auth-content'); ?>
<div class="bg-white rounded-lg shadow p-8">
    <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Mot de passe oublié</h1>
    
    <p class="text-sm text-gray-600 mb-6">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
    
    <form method="POST" action="<?php echo e(route('password.email')); ?>">
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
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Envoyer le lien
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="<?php echo e(route('login')); ?>" class="text-sm text-indigo-600 hover:text-indigo-900">← Retour à la connexion</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>