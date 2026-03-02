<?php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $applications = UserApplication::with(['offer', 'offer.company'])
            ->where('user_id', $userId)
            ->orderBy('applied_at', 'desc')
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'cover_letter' => 'nullable|string|max:5000',
            'external_url' => 'nullable|url',
        ]);

        $userId = auth()->id();
        $offerId = $request->input('offer_id');

        $existing = UserApplication::where('user_id', $userId)
            ->where('offer_id', $offerId)
            ->first();

        if ($existing) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre');
        }

        UserApplication::create([
            'user_id' => $userId,
            'offer_id' => $offerId,
            'cover_letter' => $request->input('cover_letter'),
            'external_url' => $request->input('external_url'),
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return redirect()->route('applications.index')->with('success', 'Candidature envoyée');
    }

    public function show(UserApplication $application): View
    {
        $application->load(['offer', 'offer.company', 'user']);

        return view('applications.show', compact('application'));
    }

    public function updateStatus(Request $request, UserApplication $application): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,withdrawn',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour');
    }

    public function withdraw(UserApplication $application): RedirectResponse
    {
        $application->update(['status' => 'withdrawn']);

        return redirect()->route('applications.index')->with('success', 'Candidature retirée');
    }

    public function destroy(UserApplication $application): RedirectResponse
    {
        $application->delete();

        return redirect()->route('applications.index')->with('success', 'Candidature supprimée');
    }
}
