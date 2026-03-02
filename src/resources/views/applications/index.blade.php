@extends('layouts.app')

@section('title', 'Mes candidatures')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-2">
            <i class="bi bi-envelope mr-2"></i>Mes candidatures
        </h1>
        <p class="text-indigo-200">Suivez l'état de vos candidature</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="bi bi-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @forelse($applications as $application)
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden mb-4">
            <div class="p-6">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($application->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($application->status == 'accepted') bg-green-100 text-green-700
                            @elseif($application->status == 'rejected') bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-700 @endif">
                            @if($application->status == 'pending') En attente
                            @elseif($application->status == 'accepted') Acceptée
                            @elseif($application->status == 'rejected') Refusée
                            @else Retirée
                            @endif
                        </span>
                        <span class="text-slate-400 text-sm ml-3">
                            <i class="bi bi-calendar3 mr-1"></i>{{ $application->applied_at?->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $application->offer?->title }}</h3>
                <div class="flex flex-wrap gap-4 text-slate-500 text-sm mb-4">
                    <span><i class="bi bi-building mr-1"></i>{{ $application->offer?->company?->name }}</span>
                    <span><i class="bi bi-geo-alt mr-1"></i>{{ $application->offer?->region?->name }}</span>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('offers.show', $application->offer) }}" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                        <i class="bi bi-eye mr-2"></i>Voir l'offre
                    </a>
                    @if($application->status == 'pending')
                        <form action="{{ route('applications.withdraw', $application) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette candidature ?')">
                                <i class="bi bi-x-circle mr-2"></i>Retirer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
            <i class="bi bi-envelope text-slate-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">Aucune candidature</h3>
            <p class="text-slate-500 mb-6">Vous n'avez pas encore postulé à une offre.</p>
            <a href="{{ route('offers.index') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition">
                <i class="bi bi-search mr-2"></i>Parcourir les offres
            </a>
        </div>
    @endforelse

    @if($applications->hasPages())
        <div class="mt-8">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
