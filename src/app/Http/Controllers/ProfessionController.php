<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Profession::with('skills');

        if ($request->has('search') && $request->search) {
            $searchTerms = explode(' ', trim($request->search));

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    if (empty($term)) {
                        continue;
                    }

                    $q->orWhere(function ($subQ) use ($term) {
                        $subQ->where('name', 'like', "%{$term}%")
                            ->orWhere('description', 'like', "%{$term}%")
                            ->orWhere('rome_code', 'like', "%{$term}%");
                    });
                }
            });
        }

        $professions = $query->orderBy('name')->paginate(20);
        $skills = Skill::all();

        return view('professions.index', compact('professions', 'skills'));
    }

    public function show(Profession $profession): View
    {
        $profession->load(['skills', 'formations', 'offers' => function ($query) {
            $query->active()->limit(10);
        }]);

        return view('professions.show', compact('profession'));
    }

    public function formations(Profession $profession): JsonResponse
    {
        $formations = $profession->formations()->get();

        return response()->json($formations);
    }
}
