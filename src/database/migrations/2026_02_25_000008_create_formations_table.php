<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('level')->nullable();
            $table->string('duration')->nullable();
            $table->string('school')->nullable();
            $table->string('city')->nullable();
            $table->string('url')->nullable();
            $table->enum('type', ['initial', 'alternance', 'continue'])->default('initial');
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
