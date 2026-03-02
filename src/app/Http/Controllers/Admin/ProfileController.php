<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SecurityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(public SecurityLogger $securityLogger) {}

    public function edit(): View
    {
        return view('admin.profile.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'string', 'confirmed', 'min:8'];
        }

        $request->validate($rules);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        if ($request->filled('password')) {
            $newPassword = $request->input('password');
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            $this->securityLogger->logPasswordChanged($user->id, $request->ip());

            $user->tokens()->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Auth::logout();

            return redirect('/login')->with('success', 'Mot de passe modifié. Veuillez vous reconnecter.');
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
