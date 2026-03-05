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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            
            // --- LIEN AVEC L'ORGANISME (Entreprise) ---
            // On lie la formation à l'entité qui la propose (ex: Wild Code School)
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');

            // --- DETAILS DE LA FORMATION ---
            $table->string('intitule');       // ex: Développeur Web et Mobile
            $table->text('description');      // Le programme en quelques mots
            $table->string('niveau');         // ex: Bac+2, Bachelor, Master
            $table->string('duree');          // ex: 5 mois, 2 ans
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};