<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_ingreso.admission_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('career_id')->constrained('core.careers')->restrictOnDelete();
            $table->integer('vacancies')->default(0);
            $table->string('modality', 50)->default('Ordinario');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('borrador');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_ingreso.admission_processes');
    }
};
