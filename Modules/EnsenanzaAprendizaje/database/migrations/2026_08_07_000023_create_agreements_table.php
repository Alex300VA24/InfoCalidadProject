<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.agreements', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('institution', 200);
            $table->string('type', 30)->default('nacional');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('vigente');
            $table->string('document_path', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.agreements');
    }
};
