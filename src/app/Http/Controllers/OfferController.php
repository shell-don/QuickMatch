<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Offer;
use App\Models\Profession;
use App\Models\Region;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(Request $request): View
    {
        $query = Offer::with(['company', 'region', 'skills'])->active();

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('region') && $request->region) {
            $query->where('region_id', $request->region);
        }

        if ($request->has('search') && $request->search) {
            $searchTerms = explode(' ', trim($request->search));

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    if (empty($term)) {
                        continue;
                    }

                    $q->orWhere(function ($subQ) use ($term) {
                        $subQ->where('title', 'like', "%{$term}%")
                            ->orWhere('description', 'like', "%{$term}%")
                            ->orWhere('requirements', 'like', "%{$term}%")
                            ->orWhereHas('company', function ($companyQ) use ($term) {
                                $companyQ->where('name', 'like', "%{$term}%");
                            })
                            ->orWhereHas('region', function ($regionQ) use ($term) {
                                $regionQ->where('name', 'like', "%{$term}%");
                            })
                            ->orWhereHas('skills', function ($skillQ) use ($term) {
                                $skillQ->where('name', 'like', "%{$term}%");
                            });
                    });
                }
            });
        }

        if ($request->has('skill') && $request->skill) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skills.id', $request->skill);
            });
        }

        if ($request->has('remote') && $request->remote) {
            $query->where('is_remote', true);
        }

        if ($request->has('profession') && $request->profession) {
            $query->where('profession_id', $request->profession);
        }

        if ($request->has('salary_min') && $request->salary_min) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        if ($request->has('salary_max') && $request->salary_max) {
            $query->where('salary_max', '<=', $request->salary_max);
        }

        $offers = $query->orderBy('created_at', 'desc')->paginate(12);
        $regions = Region::all();
        $skills = Skill::all();
        $professions = Profession::all();

        return view('offers.index', compact('offers', 'regions', 'skills', 'professions'));
    }

    public function show(Offer $offer): View
    {
        $offer->load(['company', 'region', 'skills', 'profession']);

        return view('offers.show', compact('offer'));
    }

    public function create(): View
    {
        $regions = Region::all();

        $user = auth()->user();
        if ($user->hasRole('manager') && $user->company) {
            $companies = Company::where('id', $user->company->id)->get();
        } else {
            $companies = Company::all();
        }

        return view('offers.create', compact('regions', 'companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'type' => 'required|in:stage,alternance,cdi,cdd,job',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'duration' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'is_remote' => 'boolean',
            'company_id' => 'required|exists:companies,id',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        $duplicate = Offer::where('title', $validated['title'])
            ->where('description', $validated['description'])
            ->where('requirements', $validated['requirements'] ?? '')
            ->where('type', $validated['type'])
            ->where('company_id', $validated['company_id'])
            ->where('region_id', $validated['region_id'] ?? null)
            ->where('salary_min', $validated['salary_min'] ?? null)
            ->where('salary_max', $validated['salary_max'] ?? null)
            ->where('duration', $validated['duration'] ?? null)
            ->where('start_date', $validated['start_date'] ?? null)
            ->where('is_remote', $validated['is_remote'] ?? false)
            ->first();

        if ($duplicate) {
            return back()->with('error', 'Cette offre existe déjà')->withInput();
        }

        $validated['status'] = 'active';
        $validated['created_by'] = auth()->user()?->id;

        $offer = Offer::create($validated);

        if ($request->has('skills')) {
            $offer->skills()->attach($request->skills);
        }

        return redirect()->route('offers.show', $offer)->with('success', 'Offre créée avec succès');
    }

    public function edit(Offer $offer): View
    {
        $regions = Region::all();
        $companies = Company::all();

        return view('offers.edit', compact('offer', 'regions', 'companies'));
    }

    public function update(Request $request, Offer $offer): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'type' => 'required|in:stage,alternance,cdi,cdd,job',
            'status' => 'in:active,closed,expired',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'duration' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'is_remote' => 'boolean',
            'company_id' => 'required|exists:companies,id',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        $offer->update($validated);

        if ($request->has('skills')) {
            $offer->skills()->sync($request->skills);
        }

        return redirect()->route('offers.show', $offer)->with('success', 'Offre mise à jour');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        if (! auth()->user()->hasRole('admin') && $offer->created_by !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette offre');
        }

        $offer->delete();

        return redirect()->back()->with('success', 'Offre supprimée');
    }
}
