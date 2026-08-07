<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.technical_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_review_id')->constrained('app_gestion_curricular.curriculum_reviews')->cascadeOnDelete();
            $table->foreignId('preparer_id')->constrained('core.users')->restrictOnDelete();
            $table->text('content');
            $table->string('status', 20)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.technical_reports');
    }
};
