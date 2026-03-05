<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        // Tableaux de données pour varier les plaisirs
        $secteurs = ['Aéronautique', 'Cybersécurité', 'Intelligence Artificielle', 'Santé connectée', 'Énergie Verte', 'Banque Digitale', 'Logistique', 'E-commerce'];
        $villes = ['Toulouse', 'Blagnac', 'Labège', 'Colomiers', 'Muret', 'Balma', 'L\'Union'];
        $metiers = ['Développeur Fullstack', 'Data Analyst', 'Ingénieur Systèmes', 'Expert Cloud', 'Designer UX/UI', 'Chef de projet Agiles', 'Consultant SEO', 'Analyste SOC'];
        $niveaux = ['Bac+2', 'Bac+3', 'Bac+5'];

        // 1. CRÉATION DE 120 ENTREPRISES
        for ($i = 1; $i <= 120; $i++) {
            $estEcole = ($i % 5 == 0); // 1 sur 5 est une école (soit ~24 écoles)
            $secteur = $secteurs[array_rand($secteurs)];

            $entId = DB::table('entreprises')->insertGetId([
                'siren' => str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'nom_complet' => ($estEcole ? "Institut " : "Global ") . Str::random(6) . ($estEcole ? " Formations" : " Tech"),
                'secteur' => $secteur,
                'adresse' => rand(1, 200) . " Avenue de l'Europe",
                'ville' => $villes[array_rand($villes)],
                'code_postal' => '31' . rand(100, 900),
                'est_organisme_formation' => $estEcole,
                'email_Ent' => "contact" . $i . "@" . ($estEcole ? "edu-toulouse.fr" : "entreprise-31.com"),
                'created_at' => now(),
            ]);

            // 2. SI C'EST UNE ENTREPRISE -> ON CRÉE 1 OU 2 OFFRES (Total ~150-180 offres)
            if (!$estEcole) {
                $nbOffres = rand(1, 2);
                for ($j = 0; $j < $nbOffres; $j++) {
                    DB::table('offres')->insert([
                        'entreprise_id' => $entId,
                        'titre' => "Stage " . $metiers[array_rand($metiers)],
                        'description' => "Rejoignez notre équipe en " . $secteur . " pour un projet innovant.",
                        'duree' => rand(3, 6) . " mois",
                        'type_contrat' => 'Stage',
                        'created_at' => now(),
                    ]);
                }
            } 
            // 3. SI C'EST UNE ÉCOLE -> ON CRÉE 2 FORMATIONS (Total ~48-50 formations)
            else {
                for ($k = 0; $k < 2; $k++) {
                    DB::table('formations')->insert([
                        'entreprise_id' => $entId,
                        'intitule' => "Expert en " . $secteur,
                        'description' => "Formation certifiante de haut niveau.",
                        'niveau' => $niveaux[array_rand($niveaux)],
                        'duree' => rand(1, 3) . " ans",
                        'created_at' => now(),
                    ]);
                }
            }
        }
    }
}