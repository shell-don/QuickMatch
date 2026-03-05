<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cloudstudio\Ollama\Facades\Ollama;

class OllamaController extends Controller
{
    public function index() { return view('ia-assistant'); }

    public function ask(Request $request)
    {
        // Supprime la limite des 60 secondes pour éviter l'Erreur 500
        set_time_limit(180); 
        
        $questionBrute = $request->input('question');

        try {
            // ÉTAPE 1 : Nettoyage sémantique
            $correction = Ollama::model('llama3')
                ->prompt("Extrait les mots-clés de cette phrase, corrige l'orthographe, et répond UNIQUEMENT les mots séparés par une virgule : " . $questionBrute)
                ->ask();
            
            $cle = trim(explode(',', $correction['response'])[0] ?? $questionBrute);

            // ÉTAPE 2 : Récupération des données (Retrieval)
            $entreprises = DB::table('entreprises')
                ->where('nom_complet', 'LIKE', "%$cle%")
                ->orWhere('secteur', 'LIKE', "%$cle%")
                ->orWhere('ville', 'LIKE', "%$cle%")
                ->limit(4)->get();

            // ÉTAPE 3 : Construction du contexte
            $contexte = "Données réelles de NOTRE base de données :\n";
            foreach ($entreprises as $e) {
                $contexte .= "- {$e->nom_complet}, secteur {$e->secteur} à {$e->ville}. Email: {$e->email_Ent}\n";
            }

            // ÉTAPE 4 : Génération avec le ton corrigé
            $promptFinal = "Tu es l'assistant de 'TrouveTonStage'. 
            Réponds à la question en utilisant UNIQUEMENT le contexte suivant.
            CONSIGNE STRICTE : Commence ta réponse par 'Dans le contexte des données réelles de notre base...' 
            Ne dis JAMAIS 'votre base'.
            
            CONTEXTE : $contexte
            QUESTION : $questionBrute";

            $resultat = Ollama::model('llama3')->prompt($promptFinal)->ask();

            return view('ia-assistant', [
                'reponse' => $resultat['response'],
                'question' => $questionBrute,
                'entreprises' => $entreprises
            ]);

        } catch (\Exception $e) {
            return view('ia-assistant', ['error' => "Erreur de connexion avec l'IA."]);
        }
    }
}