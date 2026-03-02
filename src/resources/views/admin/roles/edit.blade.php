@extends('admin.layouts.admin')

@section('title', 'Modifier le rôle')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Modifier le rôle</h1>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6 bg-white p-6 shadow rounded-lg">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nom du rôle</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
            <div class="space-y-3">
                @foreach($permissions as $category => $categoryPermissions)
                <div>
                    <h3 class="text-sm font-medium text-gray-900 capitalize mb-2">{{ $category }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categoryPermissions as $permission)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Mettre à jour</button>
            <a href="{{ route('admin.roles.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Annuler</a>
        </div>
    </form>
</div>
@endsection
