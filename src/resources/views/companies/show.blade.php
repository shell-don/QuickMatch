@extends('layouts.app')

@section('title', $company->name)

@section('content')
<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <x-layout.breadcrumbs :items="[
            ['label' => 'Entreprises', 'url' => route('companies.index')],
            ['label' => $company->name]
        ]" />
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <i class="bi bi-building text-indigo-600 text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">{{ $company->name }}</h1>
                        @if($company->industry)
                            <p class="text-slate-500">{{ $company->industry }}</p>
                        @endif
                    </div>
                </div>

                @if($company->description)
                    <h3 class="text-lg font-semibold text-slate-800 mb-3">À propos</h3>
                    <p class="text-slate-600 mb-6 whitespace-pre-line">{{ $company->description }}</p>
                @endif

                <div class="grid md:grid-cols-3 gap-4">
                    @if($company->size)
                        <div>
                            <p class="text-sm text-slate-500 mb-1"><i class="bi bi-people mr-2"></i><strong>Taille:</strong></p>
                            <p class="text-slate-700">{{ $company->size }}</p>
                        </div>
                    @endif
                    @if($company->region)
                        <div>
                            <p class="text-sm text-slate-500 mb-1"><i class="bi bi-geo-alt mr-2"></i><strong>Localisation:</strong></p>
                            <p class="text-slate-700">{{ $company->region->name }}</p>
                        </div>
                    @endif
                    @if($company->website)
                        <div>
                            <p class="text-sm text-slate-500 mb-1"><i class="bi bi-globe mr-2"></i><strong>Site web:</strong></p>
                            <a href="{{ $company->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 truncate block">{{ $company->website }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Offres ({{ $company->offers->count() }})
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($company->offers as $offer)
                        <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition">
                            <div>
                                <p class="font-medium text-slate-800">{{ $offer->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $offer->type == 'alternance' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ ucfirst($offer->type) }}
                                    </span>
                                    @if($offer->region)
                                        <span class="text-slate-400 text-sm"><i class="bi bi-geo-alt mr-1"></i>{{ $offer->region->name }}</span>
                                    @endif
                                </div>
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
    </div>
</div>
@endsection
