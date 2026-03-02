<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'role' => 'user',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'role' => 'user',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $userLinks = [
        ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'bi-speedometer2', 'active' => 'dashboard'],
        ['label' => 'Mes Candidatures', 'url' => route('applications.index'), 'icon' => 'bi-envelope', 'active' => 'applications.*'],
    ];

    $studentLinks = [
        ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'bi-speedometer2', 'active' => 'dashboard'],
        ['label' => 'Mes Candidatures', 'url' => route('applications.index'), 'icon' => 'bi-envelope', 'active' => 'applications.*'],
    ];

    $managerLinks = [
        ['label' => 'Dashboard', 'url' => route('manager.dashboard'), 'icon' => 'bi-speedometer2', 'active' => 'manager.dashboard'],
    ];

    $adminLinks = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-speedometer2', 'active' => 'admin.dashboard'],
        ['label' => 'Utilisateurs', 'url' => route('admin.users.index'), 'icon' => 'bi-people', 'active' => 'admin.users.*'],
        ['label' => 'Rôles', 'url' => route('admin.roles.index'), 'icon' => 'bi-shield-check', 'active' => 'admin.roles.*'],
        ['label' => 'Clés API', 'url' => route('admin.api-keys.index'), 'icon' => 'bi-key', 'active' => 'admin.api-keys.*'],
        ['label' => 'Offres', 'url' => route('offers.index'), 'icon' => 'bi-briefcase', 'active' => 'offers.*'],
        ['label' => 'Entreprises', 'url' => route('companies.index'), 'icon' => 'bi-building', 'active' => 'companies.*'],
    ];

    $links = match($role) {
        'admin' => $adminLinks,
        'manager' => $managerLinks,
        'étudiant' => $studentLinks,
        default => $userLinks,
    };
?>

<aside class="w-64 bg-white shadow-sm h-full flex flex-col">
    <!-- Navigation Links -->
    <nav class="mt-4 px-3 flex-1 overflow-y-auto">
        <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($link['url']); ?>" 
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg mb-1 transition-colors duration-200
               <?php echo e(request()->routeIs($link['active']) 
                    ? 'bg-indigo-50 text-indigo-700' 
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'); ?>">
                <i class="bi <?php echo e($link['icon']); ?> mr-3"></i>
                <?php echo e($link['label']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>
    
    <!-- Logout Button -->
    <div class="p-4 border-t border-slate-200">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                <i class="bi bi-box-arrow-right mr-3"></i>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
<?php /**PATH /Users/mathis/Documents/Informatique/AUTRES/PHP/Laravel/SmartIntern/resources/views/components/layout/sidebar.blade.php ENDPATH**/ ?>