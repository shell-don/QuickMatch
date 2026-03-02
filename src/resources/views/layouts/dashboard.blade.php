<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartIntern')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-gradient-to-r from-slate-900 to-slate-950 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-bold text-xl">
                    <i class="text-indigo-400 bi bi-briefcase-fill"></i>
                    SmartIntern
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('offers.index') }}" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition {{ request()->routeIs('offers.index') ? 'bg-white/10 text-white' : '' }}">
                        <i class="bi bi-search mr-1"></i> Offres
                    </a>
                    <a href="{{ route('professions.index') }}" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition {{ request()->routeIs('professions.index') ? 'bg-white/10 text-white' : '' }}">
                        <i class="bi bi-briefcase mr-1"></i> Métiers
                    </a>
                    <a href="{{ route('formations.index') }}" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition {{ request()->routeIs('formations.index') ? 'bg-white/10 text-white' : '' }}">
                        <i class="bi bi-mortarboard mr-1"></i> Formations
                    </a>
                    <a href="{{ route('chatbot.index') }}" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition {{ request()->routeIs('chatbot.index') ? 'bg-white/10 text-white' : '' }}">
                        <i class="bi bi-robot mr-1"></i> Assistant
                    </a>
                    <a href="{{ route('news.index') }}" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition {{ request()->routeIs('news.index') ? 'bg-white/10 text-white' : '' }}">
                        <i class="bi bi-newspaper mr-1"></i> Actualités
                    </a>
                </div>

                <!-- Auth Section -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ route('applications.index') }}" class="px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition">
                            <i class="bi bi-envelope mr-1"></i> Candidatures
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-slate-300 hover:text-white">
                            <i class="bi bi-person-circle"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-slate-300 hover:text-white px-2">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-3 py-2">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with Sidebar -->
    <div class="flex pt-16 h-screen overflow-hidden">
        @auth
            @if(request()->routeIs('dashboard', 'manager.dashboard', 'applications.*', 'profile.edit'))
                @php
                    $role = 'user';
                    if (auth()->user()->hasRole('admin')) $role = 'admin';
                    elseif (auth()->user()->hasRole('manager')) $role = 'manager';
                    elseif (auth()->user()->hasRole('user') && !auth()->user()->is_company) $role = 'étudiant';
                @endphp
                <x-layout.sidebar :role="$role" />
            @endif
        @endauth

        <div class="flex-1 overflow-y-auto bg-slate-50">
            @if(session('error'))
                <x-ui.toast type="error" :message="session('error')" />
            @endif
            @if(session('warning'))
                <x-ui.toast type="warning" :message="session('warning')" />
            @endif
            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
