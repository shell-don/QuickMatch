<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Schema;

class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // NETTOYAGE : On repart de zéro
        Schema::disableForeignKeyConstraints();
        DB::table('offres')->truncate();
        DB::table('entreprises')->truncate();
        Schema::enableForeignKeyConstraints();

        $secteurs = ['Informatique', 'Cybersécurité', 'Aéronautique', 'Santé', 'Énergie'];

        for ($i = 1; $i <= 40; $i++) {
            $secteur = $faker->randomElement($secteurs);
            
            // 1. Création de l'entreprise
            $entId = DB::table('entreprises')->insertGetId([
                'nom_complet' => $faker->company,
                'secteur' => $secteur,
                'ville' => $faker->city,
                'siren' => $faker->siren,
                'email_Ent' => $faker->companyEmail,
                'est_organisme_formation' => $faker->boolean(20),
                'created_at' => now(),
            ]);

            // 2. Création d'une offre de stage (Indispensable pour la recherche)
            DB::table('offres')->insert([
                'entreprise_id' => $entId,
                'titre' => "Stage Développeur " . $secteur,
                'description' => "Mission en " . $secteur . " chez un leader du marché.",
                'created_at' => now(),
            ]);
        }
    }
}