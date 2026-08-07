<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.official_acts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('core.subjects')->restrictOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('core.users')->nullOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->string('pdf_path', 500)->nullable();
            $table->string('status', 20)->default('borrador');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('core.users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['subject_id', 'academic_period_id', 'teacher_id'], 'official_acts_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.official_acts');
    }
};
