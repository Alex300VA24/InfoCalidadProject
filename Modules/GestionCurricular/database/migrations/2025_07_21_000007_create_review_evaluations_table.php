<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.review_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_review_id')->constrained('app_gestion_curricular.curriculum_reviews')->cascadeOnDelete();
            $table->foreignId('criterion_id')->constrained('app_gestion_curricular.checklist_criteria')->restrictOnDelete();
            $table->integer('score')->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->unique(['curriculum_review_id', 'criterion_id'], 'review_evaluations_curriculum_criterion_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.review_evaluations');
    }
};
