<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('type');
            $table->string('thesis_title')->nullable();
            $table->string('advisor_id')->nullable();
            $table->date('application_date');
            $table->date('resolution_date')->nullable();
            $table->string('resolution_number')->nullable();
            $table->string('status')->default('en_tramite');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_resultados_formacion.degree_applications');
    }
};
