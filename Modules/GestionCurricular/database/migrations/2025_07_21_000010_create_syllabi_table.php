<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.syllabi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained('core.careers')->restrictOnDelete();
            $table->foreignId('subject_id')->constrained('core.subjects')->restrictOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('teacher_id')->constrained('core.users')->restrictOnDelete();
            $table->string('version', 20)->default('1.0');
            $table->string('filename', 255);
            $table->string('file_path', 500);
            $table->integer('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->boolean('is_visado')->default(false);
            $table->timestamp('visado_at')->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'academic_period_id', 'version'], 'syllabi_subject_period_version_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.syllabi');
    }
};
