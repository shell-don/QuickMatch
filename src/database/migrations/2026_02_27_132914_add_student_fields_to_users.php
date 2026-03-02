<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('search_type', ['stage', 'alternance', 'premier_emploi', 'cdi', 'cdd', 'interim'])->nullable()->after('bio');
            $table->date('availability_date')->nullable()->after('search_type');
            $table->string('cv_path')->nullable()->after('availability_date');
            $table->boolean('driving_license')->default(false)->after('cv_path');
            $table->boolean('is_available')->default(false)->after('driving_license');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['search_type', 'availability_date', 'cv_path', 'driving_license', 'is_available']);
        });
    }
};
