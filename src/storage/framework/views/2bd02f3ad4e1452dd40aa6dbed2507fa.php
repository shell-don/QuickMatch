<?php $__env->startSection('title', 'Dashboard Manager'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8">
    <!-- Welcome -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Bienvenue, <?php echo e(auth()->user()->name); ?> !</h1>
        <p class="text-slate-500 mt-1">Gérez vos offres et candidatures</p>
    </div>

    <?php if(!$myCompany): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-yellow-800">Aucune entreprise associée</h3>
                    <p class="text-yellow-700 mt-1">Contactez un administrateur pour associer votre compte à une entreprise.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Total Offres</p>
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['totalOffers']); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-briefcase text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Offres Actives</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e($stats['activeOffers']); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Candidatures</p>
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['totalApplications']); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-envelope text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold text-yellow-600"><?php echo e($stats['pendingApplications']); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- My Offers -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Mes Offres
                    </h2>
                    <a href="<?php echo e(route('offers.create')); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm">
                        <i class="bi bi-plus-circle mr-1"></i>Nouvelle offre
                    </a>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $myOffers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-b border-slate-100 py-3 last:border-0">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-800 line-clamp-1"><?php echo e($offer->title); ?></h3>
                                <p class="text-sm text-slate-500"><?php echo e($offer->region?->name ?? 'France'); ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    <?php if($offer->is_active): ?> bg-green-100 text-green-700 <?php else: ?> bg-slate-100 text-slate-700 <?php endif; ?>">
                                    <?php echo e($offer->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                                <form action="<?php echo e(route('offers.destroy', $offer)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-500 py-4">Aucune offre pour le moment.</p>
                <?php endif; ?>
            </div>

            <!-- Applications -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-4">
                    <i class="bi bi-envelope mr-2"></i>Candidatures reçues
                </h2>
                <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-b border-slate-100 py-3 last:border-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-slate-800"><?php echo e($application->offer?->title ?? 'Offre supprimée'); ?></h3>
                                <p class="text-sm text-slate-500">Par: <?php echo e($application->user?->name ?? 'Utilisateur supprimé'); ?></p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                <?php if($application->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($application->status === 'accepted'): ?> bg-green-100 text-green-700
                                <?php elseif($application->status === 'rejected'): ?> bg-red-100 text-red-700
                                <?php else: ?> bg-slate-100 text-slate-700 <?php endif; ?>">
                                <?php if($application->status === 'pending'): ?> En attente
                                <?php elseif($application->status === 'accepted'): ?> Acceptée
                                <?php elseif($application->status === 'rejected'): ?> Refusée
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-500 py-4">Aucune candidature pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/dashboard/manager.blade.php ENDPATH**/ ?>