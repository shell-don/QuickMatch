@extends('layouts.dashboard')

@section('title', 'Dashboard Manager')

@section('content')
<div class="p-8">
    <!-- Welcome -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Bienvenue, {{ auth()->user()->name }} !</h1>
        <p class="text-slate-500 mt-1">Gérez vos offres et candidatures</p>
    </div>

    @if(!$myCompany)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-yellow-800">Aucune entreprise associée</h3>
                    <p class="text-yellow-700 mt-1">Contactez un administrateur pour associer votre compte à une entreprise.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Total Offres</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['totalOffers'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-briefcase text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Offres Actives</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['activeOffers'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Candidatures</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['totalApplications'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-envelope text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">En attente</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pendingApplications'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- My Offers -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-slate-800">
                        <i class="bi bi-briefcase mr-2"></i>Mes Offres
                    </h2>
                    <a href="{{ route('offers.create') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                        <i class="bi bi-plus-circle mr-1"></i>Nouvelle offre
                    </a>
                </div>
                @forelse($myOffers as $offer)
                    <div class="border-b border-slate-100 py-3 last:border-0">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-800 line-clamp-1">{{ $offer->title }}</h3>
                                <p class="text-sm text-slate-500">{{ $offer->region?->name ?? 'France' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($offer->is_active) bg-green-100 text-green-700 @else bg-slate-100 text-slate-700 @endif">
                                    {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <form action="{{ route('offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 py-4">Aucune offre pour le moment.</p>
                @endforelse
            </div>

            <!-- Applications -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-4">
                    <i class="bi bi-envelope mr-2"></i>Candidatures reçues
                </h2>
                @forelse($applications as $application)
                    <div class="border-b border-slate-100 py-3 last:border-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-slate-800">{{ $application->offer?->title ?? 'Offre supprimée' }}</h3>
                                <p class="text-sm text-slate-500">Par: {{ $application->user?->name ?? 'Utilisateur supprimé' }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($application->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($application->status === 'accepted') bg-green-100 text-green-700
                                @elseif($application->status === 'rejected') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700 @endif">
                                @if($application->status === 'pending') En attente
                                @elseif($application->status === 'accepted') Acceptée
                                @elseif($application->status === 'rejected') Refusée
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 py-4">Aucune candidature pour le moment.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection
