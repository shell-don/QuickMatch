<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            
            // --- LIEN AVEC L'ENTREPRISE ---
            // Cette ligne permet de savoir quelle entreprise propose le stage
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');

            // --- DETAILS DE L'OFFRE ---
            $table->string('titre');           // ex: Développeur Web Fullstack
            $table->text('description');       // Détails des missions
            $table->string('duree');           // ex: 6 mois
            $table->string('type_contrat');    // ex: Stage, Alternance
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};