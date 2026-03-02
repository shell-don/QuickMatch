<?php $__env->startSection('title', 'Créer une Clé API'); ?>

<?php $__env->startSection('header', 'Créer une Clé API'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.api-keys.store')); ?>" method="POST" class="max-w-2xl">
    <?php echo csrf_field(); ?>

    <div class="card">
        <div class="space-y-6">
            <div>
                <label for="partner_name" class="block text-sm font-medium text-gray-700">Nom du partenaire</label>
                <input type="text" name="partner_name" id="partner_name" required class="input mt-1" value="<?php echo e(old('partner_name')); ?>">
                <?php $__errorArgs = ['partner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="partner_email" class="block text-sm font-medium text-gray-700">Email du partenaire</label>
                <input type="email" name="partner_email" id="partner_email" class="input mt-1" value="<?php echo e(old('partner_email')); ?>">
                <?php $__errorArgs = ['partner_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="plan_id" class="block text-sm font-medium text-gray-700">Plan</label>
                <select name="plan_id" id="plan_id" required class="input mt-1">
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($plan->id); ?>" <?php echo e(old('plan_id') == $plan->id ? 'selected' : ''); ?>>
                            <?php echo e($plan->name); ?> (<?php echo e($plan->max_users ?? 'Illimité'); ?> utilisateurs)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="permissions" class="block text-sm font-medium text-gray-700">Permissions</label>
                <div class="mt-2 space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="users:read" class="rounded text-indigo-600" <?php echo e(in_array('users:read', old('permissions', ['users:read'])) ? 'checked' : ''); ?>>
                        <span class="ml-2 text-sm text-gray-700">users:read</span>
                    </label>
                    <br>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="stats:read" class="rounded text-indigo-600" <?php echo e(in_array('stats:read', old('permissions', [])) ? 'checked' : ''); ?>>
                        <span class="ml-2 text-sm text-gray-700">stats:read</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700">Date d'expiration (optionnel)</label>
                <input type="date" name="expires_at" id="expires_at" class="input mt-1" value="<?php echo e(old('expires_at')); ?>">
                <?php $__errorArgs = ['expires_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="ip_whitelist" class="block text-sm font-medium text-gray-700">IPs autorisées (optionnel, séparées par virgule)</label>
                <input type="text" name="ip_whitelist" id="ip_whitelist" class="input mt-1" placeholder="192.168.1.1, 10.0.0.1" value="<?php echo e(old('ip_whitelist')); ?>">
                <?php $__errorArgs = ['ip_whitelist'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="send_email" value="1" class="rounded text-indigo-600" <?php echo e(old('send_email') ? 'checked' : ''); ?>>
                    <span class="ml-2 text-sm text-gray-700">Envoyer la clé par email au partenaire</span>
                </label>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-4">
        <a href="<?php echo e(route('admin.api-keys.index')); ?>" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">Créer la clé API</button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/admin/api-keys/create.blade.php ENDPATH**/ ?>