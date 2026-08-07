<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core.students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('core.users')->cascadeOnDelete();
            $table->string('codigo', 30)->unique();
            $table->string('ciclo', 10)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('estado', 20)->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core.students');
    }
};
