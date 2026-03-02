@extends('admin.layouts.admin')

@section('header', 'Utilisateurs')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
        <x-ui.button href="{{ route('admin.users.create') }}">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter
        </x-ui.button>
    </div>

    <!-- Filters -->
    <x-ui.card>
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-ui.input 
                    name="search" 
                    placeholder="Rechercher par nom ou email..." 
                    value="{{ $search }}"
                />
            </div>
            <div class="w-full sm:w-48">
                <x-ui.select 
                    name="role" 
                    :options="$roles->pluck('name', 'name')->prepend('Tous les rôles', '')"
                    selected="{{ $roleFilter }}"
                />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit">
                    Rechercher
                </x-ui.button>
                @if($search || $roleFilter)
                    <x-ui.button href="{{ route('admin.users.index') }}" variant="secondary">
                        Réinitialiser
                    </x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <!-- Users Table -->
    <x-ui.card :padding="false">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <x-ui.badge variant="{{ $role->name === 'admin' ? 'indigo' : 'default' }}">
                                            {{ $role->name }}
                                        </x-ui.badge>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Modifier
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 font-medium"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
@endsection
