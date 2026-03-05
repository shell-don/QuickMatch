<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Création de l'utilisateur de test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. AJOUTE CETTE LIGNE : Elle appelle ton fichier EntrepriseSeeder
        $this->call([
            EntrepriseSeeder::class,
        ]);
    }
}