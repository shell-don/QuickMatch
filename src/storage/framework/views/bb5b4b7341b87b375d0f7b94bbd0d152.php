<?php $__env->startSection('title', 'Clés API'); ?>

<?php $__env->startSection('header', 'Gestion des Clés API'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <a href="<?php echo e(route('admin.api-keys.create')); ?>" class="btn btn-primary">
        Créer une clé API
    </a>
</div>

<div class="card">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partenaire</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clé</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expire</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $apiKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apiKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($apiKey->partner_name); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($apiKey->partner_email); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            <?php echo e($apiKey->plan->name); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($apiKey->getKeyForDisplay()); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($apiKey->is_active): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($apiKey->expires_at?->format('d/m/Y') ?? 'Jamais'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex gap-2">
                            <form action="<?php echo e(route('admin.api-keys.toggle', $apiKey)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                    <?php echo e($apiKey->is_active ? 'Désactiver' : 'Activer'); ?>

                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.api-keys.regenerate', $apiKey)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                    Régénérer
                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.api-keys.destroy', $apiKey)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Aucune clé API trouvée.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4">
    <?php echo e($apiKeys->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/admin/api-keys/index.blade.php ENDPATH**/ ?>