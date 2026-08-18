<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_resultados_formacion.graduates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('core.students')->cascadeOnDelete();
            $table->date('graduation_date')->nullable();
            $table->string('work_status')->default('no_especificado');
            $table->string('employer')->nullable();
            $table->string('job_position')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->date('survey_date')->nullable();
            $table->string('employment_relationship')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_resultados_formacion.graduates');
    }
};
