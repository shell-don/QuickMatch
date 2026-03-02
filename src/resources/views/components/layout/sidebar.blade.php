@props([
    'role' => 'user',
])

@php
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
@endphp

<aside class="w-64 bg-white shadow-sm h-full flex flex-col">
    <!-- Navigation Links -->
    <nav class="mt-4 px-3 flex-1 overflow-y-auto">
        @foreach($links as $link)
            <a href="{{ $link['url'] }}" 
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg mb-1 transition-colors duration-200
               {{ request()->routeIs($link['active']) 
                    ? 'bg-indigo-50 text-indigo-700' 
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="bi {{ $link['icon'] }} mr-3"></i>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
    
    <!-- Logout Button -->
    <div class="p-4 border-t border-slate-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                <i class="bi bi-box-arrow-right mr-3"></i>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
