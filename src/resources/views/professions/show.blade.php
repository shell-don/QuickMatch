@extends('layouts.app')

@section('title', $profession->name)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <x-layout.breadcrumbs :items="[
            ['label' => 'Métiers', 'url' => route('professions.index')],
            ['label' => $profession->name]
        ]" />
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-slate-800 mb-2">{{ $profession->name }}</h1>
                @if($profession->rome_code)
                    <p class="text-slate-500 mb-6">Code ROME: {{ $profession->rome_code }}</p>
                @endif
                
                @if($profession->description)
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line">{{ $profession->description }}</p>
                @endif

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    @forelse($profession->skills as $skill)
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm">{{ $skill->name }}</span>
                    @empty
                        <span class="text-slate-400">Aucune compétence spécifiée</span>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Offres associées
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($profession->offers as $offer)
                        <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition">
                            <div>
                                <p class="font-medium text-slate-800">{{ $offer->title }}</p>
                                <p class="text-slate-400 text-sm">{{ $offer->company?->name }}</p>
                            </div>
                            <a href="{{ route('offers.show', $offer) }}" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition text-sm">
                                Voir
                            </a>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400">
                            Aucune offre disponible
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-mortarboard mr-2"></i>Formations
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($profession->formations as $formation)
                        <div class="p-4 hover:bg-slate-50 transition">
                            <p class="font-medium text-slate-800">{{ $formation->title }}</p>
                            <p class="text-slate-400 text-sm">{{ $formation->school }} - {{ $formation->level }}</p>
                            <a href="{{ route('formations.show', $formation) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Voir détails</a>
                        </div>
                    @empty
                        <div class="p-4 text-center text-slate-400">
                            Aucune formation
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
