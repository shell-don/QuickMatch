@extends('layouts.app')

@section('title', 'Modifier l\'offre')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('offers.show', $offer) }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="bi bi-arrow-left mr-1"></i>Retour à l'offre
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-slate-800 mb-6">Modifier l'offre</h1>

            <form action="{{ route('offers.update', $offer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Titre de l'offre *</label>
                        <input type="text" name="title" required value="{{ old('title', $offer->title) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
                        <select name="type" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="stage" {{ $offer->type == 'stage' ? 'selected' : '' }}>Stage</option>
                            <option value="alternance" {{ $offer->type == 'alternance' ? 'selected' : '' }}>Alternance</option>
                            <option value="cdi" {{ $offer->type == 'cdi' ? 'selected' : '' }}>CDI</option>
                            <option value="cdd" {{ $offer->type == 'cdd' ? 'selected' : '' }}>CDD</option>
                            <option value="job" {{ $offer->type == 'job' ? 'selected' : '' }}>Emploi</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Statut</label>
                        <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="active" {{ $offer->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="closed" {{ $offer->status == 'closed' ? 'selected' : '' }}>Fermée</option>
                            <option value="expired" {{ $offer->status == 'expired' ? 'selected' : '' }}>Expirée</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Entreprise *</label>
                        <select name="company_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $offer->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Région</label>
                        <select name="region_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner...</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $offer->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Salaire min (€)</label>
                        <input type="number" name="salary_min" value="{{ old('salary_min', $offer->salary_min) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Salaire max (€)</label>
                        <input type="number" name="salary_max" value="{{ old('salary_max', $offer->salary_max) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Durée</label>
                        <input type="text" name="duration" value="{{ old('duration', $offer->duration) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date de début</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $offer->start_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_remote" value="1" {{ $offer->is_remote ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-slate-300 rounded">
                            <span class="text-slate-700">Télétravail possible</span>
                        </label>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description *</label>
                        <textarea name="description" required rows="6" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $offer->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Requirements</label>
                        <textarea name="requirements" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('requirements', $offer->requirements) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Enregistrer
                    </button>
                    <a href="{{ route('offers.show', $offer) }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
