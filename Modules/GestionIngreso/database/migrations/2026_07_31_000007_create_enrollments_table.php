<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_ingreso.enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->foreignId('student_id')->constrained('core.students')->restrictOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('career_id')->constrained('core.careers')->restrictOnDelete();
            $table->string('status', 20)->default('matriculado');
            $table->timestamp('enrolled_at')->nullable();
            $table->string('ficha_path', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_ingreso.enrollments');
    }
};
