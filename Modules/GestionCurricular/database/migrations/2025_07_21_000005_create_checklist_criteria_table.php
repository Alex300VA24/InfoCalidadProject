<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.checklist_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_template_id')->constrained('app_gestion_curricular.checklist_templates')->cascadeOnDelete();
            $table->string('code', 30)->nullable();
            $table->text('description');
            $table->integer('max_score')->default(5);
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.checklist_criteria');
    }
};
