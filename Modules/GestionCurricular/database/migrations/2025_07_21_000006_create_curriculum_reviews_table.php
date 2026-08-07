<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.curriculum_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_template_id')->constrained('app_gestion_curricular.checklist_templates')->restrictOnDelete();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('reviewer_id')->constrained('core.users')->restrictOnDelete();
            $table->foreignId('action_type_id')->nullable()->constrained('app_gestion_curricular.action_types')->restrictOnDelete();
            $table->foreignId('career_id')->constrained('core.careers')->restrictOnDelete();
            $table->string('status', 20)->default('draft');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.curriculum_reviews');
    }
};
