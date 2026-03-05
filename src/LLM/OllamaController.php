<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cloudstudio\Ollama\Facades\Ollama;

class OllamaController extends Controller
{
    // Affiche la page (Fixe l'erreur 500)
    public function index() { 
        return view('ia-assistant'); 
    }

    public function ask(Request $request)
    {
        set_time_limit(120);
        $questionBrute = $request->input('question');
        $model = 'llama3.2:1b'; // Utilise la version 1b pour la vitesse

        try {
            // 1. Extraction du mot-clé
            $extraction = Ollama::model($model)
                ->prompt("Extrait UNIQUEMENT le mot-clé principal de : '$questionBrute'. Réponds par UN SEUL MOT.")
                ->ask();
            $cle = trim(preg_replace('/[^a-zA-Z0-9]/', '', $extraction['response']));

            // 2. Recherche SQL JOINTE
            $resultats = DB::table('offres')
                ->join('entreprises', 'offres.entreprise_id', '=', 'entreprises.id')
                ->where('offres.titre', 'LIKE', "%$cle%")
                ->orWhere('entreprises.secteur', 'LIKE', "%$cle%")
                ->orWhere('entreprises.ville', 'LIKE', "%$cle%")
                ->select('entreprises.*', 'offres.titre as stage_titre')
                ->limit(4)
                ->get();

            // 3. Construction du contexte
            $contexte = $resultats->isEmpty() ? "Aucune donnée." : "Données réelles :\n";
            foreach ($resultats as $r) {
                $contexte .= "- {$r->stage_titre} chez {$r->nom_complet} ({$r->ville}).\n";
            }

            // 4. Réponse finale de l'IA
            $promptFinal = "Tu es l'assistant QuickMatch. Réponds à : '$questionBrute'. 
                            Utilise UNIQUEMENT ces données : $contexte. 
                            Commence par 'Dans le contexte des données réelles de notre base...'";

            $resultat = Ollama::model($model)->prompt($promptFinal)->ask();

            return view('ia-assistant', [
                'reponse' => $resultat['response'],
                'question' => $questionBrute,
                'entreprises' => $resultats
            ]);

        } catch (\Exception $e) {
            return view('ia-assistant', ['error' => "Erreur : " . $e->getMessage()]);
        }
    }
}