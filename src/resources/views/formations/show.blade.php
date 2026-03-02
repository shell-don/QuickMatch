@extends('layouts.app')

@section('title', $formation->title)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <x-layout.breadcrumbs :items="[
            ['label' => 'Formations', 'url' => route('formations.index')],
            ['label' => Str::limit($formation->title, 40)]
        ]" />
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $formation->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700' }}">
                        {{ ucfirst($formation->type) }}
                    </span>
                    @if($formation->level)
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs">
                            {{ $formation->level }}
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl font-bold text-slate-800 mb-6">{{ $formation->title }}</h1>
                
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="mb-2"><i class="bi bi-school mr-2 text-indigo-600"></i><strong>École:</strong> {{ $formation->school ?? 'Non spécifiée' }}</p>
                        <p class="mb-2"><i class="bi bi-geo-alt mr-2 text-indigo-600"></i><strong>Ville:</strong> {{ $formation->city ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        @if($formation->duration)
                            <p class="mb-2"><i class="bi bi-clock mr-2 text-indigo-600"></i><strong>Durée:</strong> {{ $formation->duration }}</p>
                        @endif
                        @if($formation->region)
                            <p class="mb-2"><i class="bi bi-map mr-2 text-indigo-600"></i><strong>Région:</strong> {{ $formation->region->name }}</p>
                        @endif
                    </div>
                </div>

                @if($formation->description)
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line">{{ $formation->description }}</p>
                @endif

                <h3 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h3>
                <div class="flex flex-wrap gap-2 mb-6">
                    @forelse($formation->skills as $skill)
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm">{{ $skill->name }}</span>
                    @empty
                        <span class="text-slate-400">Aucune compétence spécifiée</span>
                    @endforelse
                </div>

                @if($formation->url)
                    <a href="{{ $formation->url }}" target="_blank" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition">
                        <i class="bi bi-box-arrow-up-right"></i> Voir le site
                    </a>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Métiers associés
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($formation->professions as $profession)
                        <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition">
                            <div>
                                <p class="font-medium text-slate-800">{{ $profession->name }}</p>
                                @if($profession->rome_code)
                                    <p class="text-slate-400 text-sm">Code ROME: {{ $profession->rome_code }}</p>
                                @endif
                            </div>
                            <a href="{{ route('professions.show', $profession) }}" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition text-sm">
                                Voir
                            </a>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400">
                            Aucun métier associé
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
