<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.teacher_performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('core.users')->restrictOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->decimal('score', 5, 2);
            $table->string('source', 30)->default('encuesta_estudiante');
            $table->text('observations')->nullable();
            $table->date('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.teacher_performance_evaluations');
    }
};
