@extends('admin.layouts.admin')

@section('title', 'Modifier la Clé API')

@section('header', 'Modifier la Clé API')

@section('content')
<form action="{{ route('admin.api-keys.update', $apiKey) }}" method="POST" class="max-w-2xl">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="space-y-6">
            <div>
                <label for="partner_name" class="block text-sm font-medium text-gray-700">Nom du partenaire</label>
                <input type="text" name="partner_name" id="partner_name" required class="input mt-1" value="{{ old('partner_name', $apiKey->partner_name) }}">
                @error('partner_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="partner_email" class="block text-sm font-medium text-gray-700">Email du partenaire</label>
                <input type="email" name="partner_email" id="partner_email" class="input mt-1" value="{{ old('partner_email', $apiKey->partner_email) }}">
                @error('partner_email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="plan_id" class="block text-sm font-medium text-gray-700">Plan</label>
                <select name="plan_id" id="plan_id" required class="input mt-1">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $apiKey->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} ({{ $plan->max_users ?? 'Illimité' }} utilisateurs)
                        </option>
                    @endforeach
                </select>
                @error('plan_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="permissions" class="block text-sm font-medium text-gray-700">Permissions</label>
                <div class="mt-2 space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="users:read" class="rounded text-indigo-600" {{ in_array('users:read', old('permissions', $apiKey->permissions ?? [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">users:read</span>
                    </label>
                    <br>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="stats:read" class="rounded text-indigo-600" {{ in_array('stats:read', old('permissions', $apiKey->permissions ?? [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">stats:read</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                <input type="date" name="expires_at" id="expires_at" class="input mt-1" value="{{ old('expires_at', $apiKey->expires_at?->format('Y-m-d')) }}">
                @error('expires_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ip_whitelist" class="block text-sm font-medium text-gray-700">IPs autorisées (optionnel, séparées par virgule)</label>
                <input type="text" name="ip_whitelist" id="ip_whitelist" class="input mt-1" placeholder="192.168.1.1, 10.0.0.1" value="{{ old('ip_whitelist', $apiKey->ip_whitelist) }}">
                @error('ip_whitelist')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-4">
        <a href="{{ route('admin.api-keys.index') }}" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </div>
</form>
@endsection
