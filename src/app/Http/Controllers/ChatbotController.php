<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Services\SqlGenerationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    public function __construct(private SqlGenerationService $sqlService) {}

    public function index(): View
    {
        $chats = Chat::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('chatbot.index', compact('chats'));
    }

    public function ask(Request $request): JsonResponse
    {
        $request->validate([
            'question' => 'required|string|min:3|max:1000',
        ]);

        $question = $request->input('question');
        $userId = auth()->id();

        $result = $this->sqlService->processQuestion($question);

        Chat::create([
            'user_id' => $userId,
            'user_message' => $question,
            'ai_response' => $result['reformulation'] ?? $question,
            'generated_sql' => $result['sql'] ?? null,
            'sql_executed' => $result['success'] ?? false,
        ]);

        return response()->json([
            'success' => $result['success'] ?? false,
            'question' => $question,
            'reformulation' => $result['reformulation'] ?? null,
            'sql' => $result['sql'] ?? null,
            'results' => $result['results'] ?? null,
            'explanation' => $result['explanation'] ?? null,
            'error' => $result['error'] ?? null,
        ]);
    }

    public function history(): JsonResponse
    {
        $chats = Chat::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($chats);
    }

    public function clear(): RedirectResponse
    {
        Chat::where('user_id', auth()->id())->delete();

        return redirect()->route('chatbot.index')->with('success', 'Historique effacé');
    }
}
