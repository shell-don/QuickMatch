@extends('layouts.app')

@section('title', 'Modifier l\'entreprise')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('companies.show', $company) }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="bi bi-arrow-left mr-1"></i>Retour à l'entreprise
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-slate-800 mb-6">Modifier l'entreprise</h1>

            <form action="{{ route('companies.update', $company) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nom de l'entreprise *</label>
                        <input type="text" name="name" required value="{{ old('name', $company->name) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $company->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Site web</label>
                        <input type="url" name="website" value="{{ old('website', $company->website) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Taille de l'entreprise</label>
                        <select name="size" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option value="1-10" {{ $company->size == '1-10' ? 'selected' : '' }}>1-10 employés</option>
                            <option value="11-50" {{ $company->size == '11-50' ? 'selected' : '' }}>11-50 employés</option>
                            <option value="51-200" {{ $company->size == '51-200' ? 'selected' : '' }}>51-200 employés</option>
                            <option value="201-500" {{ $company->size == '201-500' ? 'selected' : '' }}>201-500 employés</option>
                            <option value="500+" {{ $company->size == '500+' ? 'selected' : '' }}>500+ employés</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Secteur d'activité</label>
                        <input type="text" name="industry" value="{{ old('industry', $company->industry) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Région</label>
                        <select name="region_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner...</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $company->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Adresse</label>
                        <input type="text" name="address" value="{{ old('address', $company->address) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email de contact</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $company->phone) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Enregistrer
                    </button>
                    <a href="{{ route('companies.show', $company) }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
