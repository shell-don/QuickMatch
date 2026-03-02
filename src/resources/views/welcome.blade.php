@extends('layouts.app')

@section('title', 'SmartIntern - Trouvez votre stage idéal')

@section('content')
@php
    $randomFormation = $formations->random() ?? null;
@endphp
<!-- Hero Section -->
<section class="min-h-screen relative">
    <!-- Solid Dark Blue Background -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900 to-slate-950">
        <!-- Decorative Circles -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-slate-700/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-slate-700/15 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-800/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 py-12 relative h-full flex items-center justify-center">
        <div class="grid lg:grid-cols-2 gap-12 items-center w-full max-w-5xl">
            <!-- Left Content -->
            <div class="text-center lg:text-left order-2 lg:order-1">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Trouvez votre <span class="text-indigo-400">match</span> professionnel
                </h1>
                <p class="text-lg text-slate-400 mb-8 max-w-xl">
                    SmartIntern vous aide à trouver le stage, l'alternance ou la formation idéale. 
                    Notre assistant IA analyse votre profil et vous propose les meilleures opportunités.
                </p>
                
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start mb-12">
                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center gap-2">
                        <i class="bi bi-rocket-takeoff"></i> Commencer maintenant
                    </a>
                    <a href="{{ route('offers.index') }}" class="border border-slate-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-white/10 transition flex items-center gap-2">
                        <i class="bi bi-search"></i> Parcourir les offres
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex justify-center lg:justify-start gap-8">
                    <div class="text-center">
                        <span class="block text-3xl font-bold text-indigo-400">{{ \App\Models\Offer::count() }}</span>
                        <span class="text-slate-500 text-sm">Offres</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-3xl font-bold text-indigo-400">{{ \App\Models\Company::count() }}</span>
                        <span class="text-slate-500 text-sm">Entreprises</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-3xl font-bold text-indigo-400">{{ \App\Models\Formation::count() }}</span>
                        <span class="text-slate-500 text-sm">Formations</span>
                    </div>
                </div>
            </div>

            <!-- Right Content - Formation Card -->
            <div class="flex flex-col items-center order-1 lg:order-2">
                
                @if($randomFormation)
                <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Image area with blue theme -->
                    <div class="relative h-56 bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-700 overflow-hidden">
                        <!-- Decorative elements -->
                        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'%3E%3Ccircle cx=\'50\' cy=\'50\' r=\'30\' fill=\'none\' stroke=\'white\' stroke-width=\'0.5\'/%3E%3Ccircle cx=\'20\' cy=\'30\' r=\'15\' fill=\'none\' stroke=\'white\' stroke-width=\'0.5\'/%3E%3Ccircle cx=\'80\' cy=\'70\' r=\'20\' fill=\'none\' stroke=\'white\' stroke-width=\'0.5\'/%3E%3C/svg%3E'); background-size: 150px;"></div>
                        <!-- Building/School silhouette -->
                        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-indigo-900/50 to-transparent"></div>
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex flex-col items-center">
                            <i class="bi bi-building text-6xl text-white/90 drop-shadow-lg"></i>
                        </div>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $randomFormation->title }}</h3>
                        <p class="text-slate-500 text-sm mb-3">
                            <i class="bi bi-building mr-1"></i>{{ $randomFormation->school }} • {{ $randomFormation->city }}
                        </p>
                        <div class="flex flex-wrap justify-center gap-2 mb-3">
                            @if($randomFormation->level)
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-medium">{{ $randomFormation->level }}</span>
                            @endif
                            @if($randomFormation->duration)
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-medium">{{ $randomFormation->duration }}</span>
                            @endif
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $randomFormation->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $randomFormation->type == 'alternance' ? 'Alternance' : 'Formation' }}
                            </span>
                        </div>
                        <div class="flex flex-wrap justify-center gap-1 mb-4">
                            @foreach($randomFormation->skills->take(3) as $skill)
                                <span class="px-2 py-1 bg-slate-50 text-slate-600 rounded text-xs">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('formations.show', $randomFormation) }}" class="inline-block w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Voir la formation
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center gap-6 mt-6">
                    <button class="w-14 h-14 rounded-full bg-white text-red-600 shadow-lg hover:scale-110 transition flex items-center justify-center text-3xl border-2 border-red-500">
                        <span class="text-red-600 font-bold">✕</span>
                    </button>
                    <button class="w-14 h-14 rounded-full bg-white text-green-600 shadow-lg hover:scale-110 transition flex items-center justify-center text-3xl border-2 border-green-500">
                        <span class="text-green-600 font-bold">♥</span>
                    </button>
                </div>
                @else
                <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl p-8 text-center">
                    <p class="text-slate-400">Aucune formation disponible</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Comment ça marche ?</h2>
            <p class="text-slate-500">Trois étapes pour trouver votre opportunité idéale</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-search text-2xl text-indigo-600"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">1. Parcourez</h3>
                <p class="text-slate-500 text-sm">Explorez les offres de stages, alternances et formations selon vos critères.</p>
            </div>
            <div class="text-center p-6 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-robot text-2xl text-indigo-600"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">2. Assistant IA</h3>
                <p class="text-slate-500 text-sm">Posez vos questions en langage naturel, notre IA trouve les meilleures réponses.</p>
            </div>
            <div class="text-center p-6 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-send text-2xl text-indigo-600"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">3. Postulez</h3>
                <p class="text-slate-500 text-sm">Candidature simplifiée et suivi de vos demandes en un seul endroit.</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Offers Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Dernières offres</h2>
            <p class="text-slate-500">Les opportunités les plus récentes</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @php
                $latestOffers = \App\Models\Offer::with('company', 'region')->active()->latest()->take(3)->get();
            @endphp
            @forelse($latestOffers as $offer)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="p-5">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium mb-3 {{ $offer->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700' }}">
                            {{ ucfirst($offer->type) }}
                        </span>
                        <h3 class="font-semibold text-lg mb-2 line-clamp-1">{{ $offer->title }}</h3>
                        <p class="text-slate-500 text-sm mb-1">
                            <i class="bi bi-building mr-1"></i>{{ $offer->company?->name }}
                        </p>
                        <p class="text-slate-500 text-sm mb-4">
                            <i class="bi bi-geo-alt mr-1"></i>{{ $offer->region?->name ?? 'France' }}
                        </p>
                        <a href="{{ route('offers.show', $offer) }}" class="block w-full text-center py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                            Voir
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8">
                    <p class="text-slate-500">Aucune offre disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('offers.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition">
                Voir toutes les offres
            </a>
        </div>
    </div>
</section>
@endsection
