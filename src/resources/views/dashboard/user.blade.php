@extends('layouts.dashboard')

@section('title', 'Mon Dashboard')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Welcome -->
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">Bienvenue, {{ auth()->user()->name }} !</h1>
        <p class="text-slate-500 mt-1">Suivez vos candidatures et trouvez votre prochain stage</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['totalApplications'] }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-envelope text-indigo-600"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-2">candidatures envoyées</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs uppercase tracking-wider">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pendingApplications'] }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-clock text-yellow-600"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-2">réponses en attente</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs uppercase tracking-wider">Acceptées</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['acceptedApplications'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-2">positifs</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs uppercase tracking-wider">Refusées</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejectedApplications'] }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-600"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-2">négatifs</p>
        </div>
    </div>

    <!-- Chart + Applications Row -->
    <div class="grid lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
        <!-- Chart -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Répartition des candidatures</h3>
            <div class="relative h-40">
                <canvas id="applicationsChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl font-bold text-slate-800">{{ $stats['totalApplications'] }}</span>
                    @if($stats['totalApplications'] > 0)
                        <span class="text-xs text-green-600 font-medium">
                            {{ round(($stats['acceptedApplications'] / $stats['totalApplications']) * 100) }}% réussite
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- My Applications -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">
                <i class="bi bi-envelope mr-2"></i>Mes Candidatures
            </h2>
            @forelse($myApplications as $application)
                <div class="border-b border-slate-100 py-3 last:border-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-slate-800">{{ $application->offer?->title ?? 'Offre supprimée' }}</h3>
                            <p class="text-sm text-slate-500">{{ $application->offer?->company?->name ?? '' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($application->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($application->status === 'accepted') bg-green-100 text-green-700
                            @elseif($application->status === 'rejected') bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-700 @endif">
                            @if($application->status === 'pending') En attente
                            @elseif($application->status === 'accepted') Acceptée
                            @elseif($application->status === 'rejected') Refusée
                            @else {{ $application->status }}
                            @endif
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-slate-500 py-4">Aucune candidature pour le moment.</p>
            @endforelse
            @if($myApplications->count() > 0)
                <a href="{{ route('applications.index') }}" class="block mt-4 text-center text-indigo-600 hover:text-indigo-800">
                    Voir toutes mes candidatures
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('applicationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Acceptées', 'En attente', 'Refusées'],
            datasets: [{
                data: [
                    {{ $stats['acceptedApplications'] }},
                    {{ $stats['pendingApplications'] }},
                    {{ $stats['rejectedApplications'] }}
                ],
                backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endsection
