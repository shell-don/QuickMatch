@extends('layouts.app')

@section('title', 'Entreprises')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="bi bi-building mr-2"></i>Entreprises
                </h1>
                <p class="text-slate-400">Découvrez les entreprises qui recrutent</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-lg">
                <form action="{{ route('companies.index') }}" method="GET" class="flex gap-3">
                    <input 
                        type="text" 
                        name="search" 
                        class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none" 
                        placeholder="Rechercher une entreprise..."
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($companies as $company)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                            <i class="bi bi-building text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">{{ $company->name }}</h3>
                            @if($company->industry)
                                <p class="text-slate-500 text-sm">{{ $company->industry }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-1 text-slate-500 text-sm mb-4">
                        @if($company->size)
                            <p><i class="bi bi-people mr-1"></i>{{ $company->size }}</p>
                        @endif
                        @if($company->region)
                            <p><i class="bi bi-geo-alt mr-1"></i>{{ $company->region->name }}</p>
                        @endif
                    </div>
                    
                    <a href="{{ route('companies.show', $company) }}" class="block w-full text-center py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                        Voir les offres <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
                    <i class="bi bi-building text-slate-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucune entreprise</h3>
                    <p class="text-slate-500">Aucune entreprise disponible pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($companies->hasPages())
        <div class="mt-8">
            {{ $companies->links() }}
        </div>
    @endif
</div>
@endsection
