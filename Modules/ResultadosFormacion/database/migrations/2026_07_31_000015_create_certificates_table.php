<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_resultados_formacion.certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('core.students')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('type');
            $table->string('concept');
            $table->date('issued_at');
            $table->string('issued_by')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('status')->default('emitido');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_resultados_formacion.certificates');
    }
};
