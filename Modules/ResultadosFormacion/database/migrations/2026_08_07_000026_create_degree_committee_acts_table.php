<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_resultados_formacion.degree_committee_acts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('degree_application_id')->constrained('app_resultados_formacion.degree_applications')->cascadeOnDelete();
            $table->string('act_type', 30)->default('sustentacion');
            $table->date('session_date')->nullable();
            $table->string('result', 20)->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_resultados_formacion.degree_committee_acts');
    }
};
