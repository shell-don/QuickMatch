<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartIntern') - Trouvez votre stage idéal</title>
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
                        <!-- Direct link to profile for all users (no dropdown) -->
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-slate-300 hover:text-white">
                            <i class="bi bi-person-circle"></i>
                            {{ auth()->user()->name }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-3 py-2">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @if(session('success'))
            <x-ui.toast type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-ui.toast type="error" :message="session('error')" />
        @endif
        @if(session('warning'))
            <x-ui.toast type="warning" :message="session('warning')" />
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h5 class="text-white font-bold mb-3">
                        <i class="bi bi-briefcase-fill text-indigo-400 mr-2"></i>SmartIntern
                    </h5>
                    <p class="text-sm">Votre assistant intelligent pour trouver le stage, l'alternance ou la formation idéale.</p>
                </div>
                <div>
                    <h6 class="text-white font-semibold mb-3">Navigation</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('offers.index') }}" class="hover:text-white transition">Offres</a></li>
                        <li><a href="{{ route('professions.index') }}" class="hover:text-white transition">Métiers</a></li>
                        <li><a href="{{ route('formations.index') }}" class="hover:text-white transition">Formations</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-white font-semibold mb-3">Services</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('chatbot.index') }}" class="hover:text-white transition">Assistant IA</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-white transition">Actualités</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-white font-semibold mb-3">Contact</h6>
                    <p class="text-sm"><i class="bi bi-envelope mr-2"></i>contact@smartintern.fr</p>
                    <p class="text-sm"><i class="bi bi-geo-alt mr-2"></i>Toulouse, France</p>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} SmartIntern. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
