@extends('layouts.app')

@section('title', 'Métiers')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Filters Horizontal Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('professions.index') }}" method="GET" class="flex gap-3">
            <input 
                type="text" 
                name="search" 
                class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm" 
                placeholder="Rechercher un métier..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- Active Filters Tags -->
    @if(request('search'))
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="text-sm text-slate-500">Filtres actifs :</span>
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                {{ request('search') }}
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-indigo-900">×</a>
            </span>
            <a href="{{ route('professions.index') }}" class="text-sm text-red-600 hover:text-red-800">
                Réinitialiser
            </a>
        </div>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($professions as $profession)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-5">
                    <h3 class="font-semibold text-lg mb-2">{{ $profession->name }}</h3>
                    
                    @if($profession->rome_code)
                        <p class="text-slate-500 text-sm mb-2">Code ROME: {{ $profession->rome_code }}</p>
                    @endif
                    
                    @if($profession->description)
                        <p class="text-slate-500 text-sm mb-3 line-clamp-3">{{ Str::limit($profession->description, 100) }}</p>
                    @endif
                    
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach($profession->skills->take(5) as $skill)
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs">{{ $skill->name }}</span>
                        @endforeach
                        @if($profession->skills->count() > 5)
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">+{{ $profession->skills->count() - 5 }}</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('professions.show', $profession) }}" class="block w-full text-center py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                        Voir les formations <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
                    <i class="bi bi-briefcase text-slate-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucun métier</h3>
                    <p class="text-slate-500">Aucun métier disponible pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($professions->hasPages())
        <div class="mt-8">
            {{ $professions->links() }}
        </div>
    @endif
</div>
@endsection
