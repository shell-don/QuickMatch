<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\RagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(private RagService $ragService) {}

    public function index(Request $request): View
    {
        $query = News::query();

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(12);

        return view('news.index', compact('news'));
    }

    public function show(News $news): View
    {
        return view('news.show', compact('news'));
    }

    public function ask(Request $request): JsonResponse
    {
        $request->validate([
            'question' => 'required|string|min:3|max:500',
        ]);

        $result = $this->ragService->answerQuestionAboutNews($request->question);

        return response()->json($result);
    }
}
