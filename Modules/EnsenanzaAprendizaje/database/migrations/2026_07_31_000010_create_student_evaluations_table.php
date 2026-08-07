<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.student_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_period_id')->constrained()->cascadeOnDelete();
            $table->string('evaluation_type');
            $table->decimal('score', 5, 2);
            $table->date('evaluation_date');
            $table->text('observations')->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('core.users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'academic_period_id', 'evaluation_type'], 'student_eval_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.student_evaluations');
    }
};
