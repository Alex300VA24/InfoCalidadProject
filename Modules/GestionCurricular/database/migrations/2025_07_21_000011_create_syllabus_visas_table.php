<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.syllabus_visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_id')->constrained('app_gestion_curricular.syllabi')->cascadeOnDelete();
            $table->foreignId('visor_id')->constrained('core.users')->restrictOnDelete();
            $table->string('status', 20)->default('pending');
            $table->text('observations')->nullable();
            $table->timestamp('visado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.syllabus_visas');
    }
};
