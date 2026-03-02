<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 bg-gradient-to-r from-slate-900 to-slate-950 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-white font-bold text-xl">
                    <i class="text-indigo-400 bi bi-briefcase-fill"></i>
                    SmartIntern
                </a>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-slate-300 hover:text-white px-3 py-2">
                        <i class="bi bi-house mr-1"></i>Accueil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-slate-300 hover:text-white">
                        <i class="bi bi-person-circle"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-300 hover:text-white px-2">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with Sidebar -->
    <div class="flex pt-16 min-h-screen">
        <x-layout.sidebar role="admin" />

        <div class="flex-1 p-8">
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
        </div>
    </div>

    @yield('scripts')
</body>
</html>
