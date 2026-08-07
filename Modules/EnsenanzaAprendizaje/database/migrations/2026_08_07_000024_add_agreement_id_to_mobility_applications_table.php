<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_ensenanza_aprendizaje.mobility_applications', function (Blueprint $table) {
            $table->foreignId('agreement_id')
                ->nullable()
                ->after('id')
                ->constrained('app_ensenanza_aprendizaje.agreements')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('app_ensenanza_aprendizaje.mobility_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('agreement_id');
        });
    }
};
