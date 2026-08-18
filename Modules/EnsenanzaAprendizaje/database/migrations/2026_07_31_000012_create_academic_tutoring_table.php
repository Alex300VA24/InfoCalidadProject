<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.academic_tutoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('core.students')->cascadeOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->cascadeOnDelete();
            $table->foreignId('tutor_id')->nullable()->constrained('core.users')->nullOnDelete();
            $table->date('tutoring_date');
            $table->string('type')->default('acompanamiento');
            $table->text('reason')->nullable();
            $table->text('outcome')->nullable();
            $table->string('status')->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.academic_tutoring');
    }
};
