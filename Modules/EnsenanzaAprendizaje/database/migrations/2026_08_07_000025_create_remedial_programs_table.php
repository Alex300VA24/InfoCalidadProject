<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.remedial_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('core.students')->cascadeOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('core.subjects')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('plan_path', 500)->nullable();
            $table->string('status', 20)->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.remedial_programs');
    }
};
