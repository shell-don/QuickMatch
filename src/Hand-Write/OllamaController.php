<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudstudio\Ollama\Facades\Ollama; // Importation vitale

class OllamaController extends Controller
{
    // Affiche la page initiale
    public function index() {
        return view('ia-assistant');
    }

    // Traite la question
    public function ask(Request $request) {
        set_time_limit(300);
        $request->validate(['question' => 'required|string']);

        try {
            $result = Ollama::model('llama3')
                ->prompt("Réponds en français : " . $request->question)
                ->ask();

            return view('ia-assistant', [
                'reponse' => $result['response'],
                'question' => $request->question
            ]);
        } catch (\Exception $e) {
            return view('ia-assistant', ['error' => "Détail : " . $e->getMessage()]);
        }
    }
}