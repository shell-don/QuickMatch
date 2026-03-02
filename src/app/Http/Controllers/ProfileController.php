<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load('skills');

        if ($user->hasRole('user') && ! $user->is_company) {
            return view('profile.student', [
                'user' => $user,
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $isStudent = $user->hasRole('user') && ! $user->is_company;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'region_id' => ['nullable', 'exists:regions,id'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['exists:skills,id'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($isStudent) {
            $rules['search_type'] = ['nullable', Rule::in(['stage', 'alternance', 'premier_emploi', 'cdi', 'cdd', 'interim'])];
            $rules['availability_date'] = ['nullable', 'date'];
            $rules['cv'] = ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'];
            $rules['driving_license'] = ['nullable', 'boolean'];
            $rules['is_available'] = ['nullable', 'boolean'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'region_id' => $validated['region_id'] ?? null,
        ]);

        if ($isStudent) {
            if (! empty($validated['search_type'])) {
                $user->update(['search_type' => $validated['search_type']]);
            }
            if (! empty($validated['availability_date'])) {
                $user->update(['availability_date' => $validated['availability_date']]);
            }
            if (isset($validated['driving_license'])) {
                $user->update(['driving_license' => $validated['driving_license']]);
            }
            if (isset($validated['is_available'])) {
                $user->update(['is_available' => $validated['is_available']]);
            }

            if ($request->hasFile('cv')) {
                if ($user->cv_path && file_exists(public_path($user->cv_path))) {
                    unlink(public_path($user->cv_path));
                }
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $user->update(['cv_path' => 'storage/'.$cvPath]);
            }
        }

        try {
            Log::info('=== DEBUG SKILLS ===');
            Log::info('User ID:', [$user->id]);
            Log::info('Is student:', [$isStudent]);
            Log::info('Skills reçus:', $validated['skills'] ?? ['aucun']);

            if (isset($validated['skills'])) {
                $user->skills()->sync($validated['skills']);
                Log::info('Skills après sync:', $user->fresh()->skills()->pluck('skill_id')->toArray());
            } else {
                $user->skills()->sync([]);
                Log::info('Skills après sync (vide):', $user->fresh()->skills()->pluck('skill_id')->toArray());
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la sync des skills:', ['error' => $e->getMessage()]);
        }

        $user->refresh();
        $user->load('skills');

        if (! empty($validated['current_password']) && ! empty($validated['password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('profile.edit');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->avatar_path && file_exists(public_path($user->avatar_path))) {
            unlink(public_path($user->avatar_path));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_path' => 'storage/'.$path]);

        return back()->with('success', 'Photo de profil mise à jour.');
    }

    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar_path && file_exists(public_path($user->avatar_path))) {
            unlink(public_path($user->avatar_path));
        }

        $user->update(['avatar_path' => null]);

        return back()->with('success', 'Photo de profil supprimée.');
    }
}
