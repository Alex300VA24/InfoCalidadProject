<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_ingreso.applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_process_id')->constrained('app_gestion_ingreso.admission_processes')->cascadeOnDelete();
            $table->string('dni', 15);
            $table->string('paterno', 100);
            $table->string('materno', 100)->nullable();
            $table->string('nombres', 100);
            $table->string('email', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->foreignId('career_id')->constrained('core.careers')->restrictOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('status', 20)->default('postulante');
            $table->string('constancia_path', 500)->nullable();
            $table->timestamps();

            $table->unique(['admission_process_id', 'dni']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_ingreso.applicants');
    }
};
