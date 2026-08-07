<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.subject_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('core.subjects')->restrictOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('core.users')->nullOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('syllabus_id')->nullable()->constrained('app_gestion_curricular.syllabi')->nullOnDelete();
            $table->decimal('progress_pct', 5, 2)->default(0);
            $table->string('status', 20)->default('en_ejecucion');
            $table->timestamps();

            $table->unique(['subject_id', 'academic_period_id', 'teacher_id'], 'subject_executions_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.subject_executions');
    }
};
