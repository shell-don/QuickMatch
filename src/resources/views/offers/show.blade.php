@extends('layouts.app')

@section('title', $offer->title)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <x-layout.breadcrumbs :items="[
            ['label' => 'Offres', 'url' => route('offers.index')],
            ['label' => Str::limit($offer->title, 40)]
        ]" />
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium mb-2 {{ $offer->type == 'alternance' ? 'bg-green-100 text-green-700' : ($offer->type == 'stage' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700') }}">
                            {{ ucfirst($offer->type) }}
                        </span>
                        <h1 class="text-2xl font-bold text-slate-800">{{ $offer->title }}</h1>
                    </div>
                    @auth
                        <form action="{{ route('applications.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center gap-2">
                                <i class="bi bi-send"></i> Postuler
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-indigo-600 hover:text-white transition flex items-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i> Connectez-vous pour postuler
                        </a>
                    @endauth
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="mb-2"><i class="bi bi-building mr-2 text-indigo-600"></i><strong>Entreprise:</strong> {{ $offer->company?->name }}</p>
                        <p class="mb-2"><i class="bi bi-geo-alt mr-2 text-indigo-600"></i><strong>Lieu:</strong> {{ $offer->region?->name ?? 'Non spécifié' }}</p>
                        @if($offer->is_remote)
                            <p class="mb-2"><i class="bi bi-house mr-2 text-green-600"></i><strong>Remote:</strong> Oui</p>
                        @endif
                    </div>
                    <div>
                        @if($offer->salary_min || $offer->salary_max)
                            <p class="mb-2"><i class="bi bi-currency-euro mr-2 text-indigo-600"></i><strong>Salaire:</strong> 
                                {{ $offer->salary_min ? $offer->salary_min . '€' : '' }} 
                                {{ $offer->salary_min && $offer->salary_max ? '-' : '' }}
                                {{ $offer->salary_max ? $offer->salary_max . '€' : '' }}
                            </p>
                        @endif
                        @if($offer->duration)
                            <p class="mb-2"><i class="bi bi-clock mr-2 text-indigo-600"></i><strong>Durée:</strong> {{ $offer->duration }}</p>
                        @endif
                        @if($offer->start_date)
                            <p class="mb-2"><i class="bi bi-calendar mr-2 text-indigo-600"></i><strong>Début:</strong> {{ $offer->start_date->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                <p class="text-slate-600 mb-6 whitespace-pre-line">{{ $offer->description }}</p>

                @if($offer->requirements)
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Profil recherché</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line">{{ $offer->requirements }}</p>
                @endif

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    @forelse($offer->skills as $skill)
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm">{{ $skill->name }}</span>
                    @empty
                        <span class="text-slate-400">Aucune compétence spécifiée</span>
                    @endforelse
                </div>

                @if($offer->source_url)
                    <a href="{{ $offer->source_url }}" target="_blank" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 transition">
                        <i class="bi bi-box-arrow-up-right"></i> Voir l'offre originale
                    </a>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="font-semibold text-slate-800 mb-4">{{ $offer->company?->name }}</h3>
                @if($offer->company?->industry)
                    <p class="text-slate-500 text-sm mb-2"><i class="bi bi-tag mr-2"></i>{{ $offer->company->industry }}</p>
                @endif
                @if($offer->company?->size)
                    <p class="text-slate-500 text-sm mb-2"><i class="bi bi-people mr-2"></i>{{ $offer->company->size }}</p>
                @endif
                @if($offer->company?->region)
                    <p class="text-slate-500 text-sm"><i class="bi bi-geo-alt mr-2"></i>{{ $offer->company->region->name }}</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h4 class="font-semibold text-slate-800 mb-3">Autres offres</h4>
                @forelse($offer->company?->offers->where('id', '!=', $offer->id)->take(3) as $otherOffer)
                    <a href="{{ route('offers.show', $otherOffer) }}" class="block py-2 border-b border-slate-100 last:border-0 hover:text-indigo-600 transition">
                        <p class="text-sm font-medium text-slate-700 line-clamp-2">{{ $otherOffer->title }}</p>
                        <p class="text-xs text-slate-400">{{ $otherOffer->region?->name }}</p>
                    </a>
                @empty
                    <p class="text-slate-400 text-sm">Aucune autre offre</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
