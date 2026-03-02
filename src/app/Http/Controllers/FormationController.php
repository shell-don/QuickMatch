<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Region;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Formation::with(['region', 'skills']);

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
                            ->orWhere('school', 'like', "%{$term}%")
                            ->orWhere('city', 'like', "%{$term}%")
                            ->orWhere('duration', 'like', "%{$term}%");
                    });
                }
            });
        }

        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('region_id') && $request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->has('skill') && $request->skill) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skills.id', $request->skill);
            });
        }

        $formations = $query->orderBy('title')->paginate(20);
        $regions = Region::all();
        $skills = Skill::all();

        return view('formations.index', compact('formations', 'regions', 'skills'));
    }

    public function show(Formation $formation): View
    {
        $formation->load(['region', 'skills', 'professions']);

        return view('formations.show', compact('formation'));
    }

    public function random(Request $request): \Illuminate\Http\JsonResponse
    {
        $count = min((int) $request->query('count', 5), 10);

        $formations = Formation::with('skills')
            ->inRandomOrder()
            ->limit($count)
            ->get()
            ->map(function ($f) {
                return [
                    'id' => $f->id,
                    'title' => $f->title,
                    'school' => $f->school,
                    'city' => $f->city,
                    'level' => $f->level,
                    'duration' => $f->duration,
                    'type' => $f->type,
                    'url' => route('formations.show', $f),
                    'skills' => $f->skills->map(fn ($s) => ['id' => $s->id, 'name' => $s->name]),
                ];
            });

        return response()->json($formations);
    }
}
