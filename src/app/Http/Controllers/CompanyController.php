<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::with('region');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->has('industry') && $request->industry) {
            $query->where('industry', $request->industry);
        }

        $companies = $query->orderBy('name')->paginate(12);

        return view('companies.index', compact('companies'));
    }

    public function show(Company $company): View
    {
        $company->load(['region', 'offers' => function ($query) {
            $query->active()->limit(10);
        }]);

        return view('companies.show', compact('company'));
    }

    public function create(): View
    {
        $regions = Region::all();

        return view('companies.create', compact('regions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'industry' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Entreprise créée');
    }

    public function edit(Company $company): View
    {
        $regions = Region::all();

        return view('companies.edit', compact('company', 'regions'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'industry' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        $company->update($validated);

        return redirect()->route('companies.show', $company)->with('success', 'Entreprise mise à jour');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Entreprise supprimée');
    }
}
