<?php $__env->startSection('title', 'Créer un rôle'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Créer un rôle</h1>

    <form action="<?php echo e(route('admin.roles.store')); ?>" method="POST" class="space-y-6 bg-white p-6 shadow rounded-lg">
        <?php echo csrf_field(); ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Nom du rôle</label>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
            <div class="space-y-3">
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-900 capitalize mb-2"><?php echo e($category); ?></h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $categoryPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600"><?php echo e($permission->name); ?></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Créer</button>
            <a href="<?php echo e(route('admin.roles.index')); ?>" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/admin/roles/create.blade.php ENDPATH**/ ?>