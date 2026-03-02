<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HasSearchable;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasSearchable;

    public function index(Request $request): View
    {
        $query = User::with('roles');

        $this->applySearch($request, $query, ['name', 'email']);

        $this->applyFilters($request, $query, [
            'role' => fn ($q, $role) => $q->whereHas('roles', fn ($q) => $q->where('name', $role)),
        ]);

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $roles = Role::all();

        return $this->view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $request->get('search', ''),
            'roleFilter' => $request->get('role', ''),
        ]);
    }

    public function create(): View
    {
        return $this->view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->validated('roles'));
        }

        return $this->success('Utilisateur créé avec succès.', 'admin.users.index');
    }

    public function edit(User $user): View
    {
        return $this->view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->validated('password')),
            ]);
        }

        $user->syncRoles($request->validated('roles', []));

        return $this->success('Utilisateur mis à jour avec succès.', 'admin.users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return $this->error('Vous ne pouvez pas supprimer votre propre compte.', 'admin.users.index');
        }

        $user->delete();

        return $this->success('Utilisateur supprimé avec succès.', 'admin.users.index');
    }
}
