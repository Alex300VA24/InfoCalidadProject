<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_resultados_formacion.graduate_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('graduate_id')->constrained('app_resultados_formacion.graduates')->cascadeOnDelete();
            $table->string('period', 20);
            $table->date('survey_date');
            $table->boolean('employed')->default(false);
            $table->boolean('job_related_to_career')->nullable();
            $table->decimal('competency_level_score', 5, 2)->nullable();
            $table->decimal('income', 10, 2)->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_resultados_formacion.graduate_surveys');
    }
};
