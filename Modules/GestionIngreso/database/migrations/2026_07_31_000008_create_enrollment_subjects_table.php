<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_ingreso.enrollment_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('app_gestion_ingreso.enrollments')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('core.subjects')->restrictOnDelete();
            $table->string('status', 20)->default('regular');
            $table->timestamps();

            $table->unique(['enrollment_id', 'subject_id'], 'enrollment_subjects_enrollment_subject_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_ingreso.enrollment_subjects');
    }
};
