<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            
            // --- Identification (Source API) ---
            $table->string('siren')->unique()->nullable(); 
            $table->string('nom_complet')->nullable();
            $table->string('secteur')->nullable(); // Ajout pour l'IA
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->boolean('est_organisme_formation')->default(false);

            // --- Infos supplémentaires (Source CSV) ---
            $table->string('Statut_social')->nullable();
            $table->string('n_tel_Ent')->nullable();
            $table->string('email_Ent')->nullable();
            $table->string('Nom_domaine')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};