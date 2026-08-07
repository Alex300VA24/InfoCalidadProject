<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.teaching_loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('core.users')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('core.subjects')->restrictOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->string('section', 20)->nullable();
            $table->decimal('hours', 5, 2)->default(0);
            $table->string('status', 20)->default('asignado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.teaching_loads');
    }
};
