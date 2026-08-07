<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core.subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained('core.careers')->cascadeOnDelete();
            $table->string('code', 20)->unique();
            $table->string('name', 255);
            $table->integer('credits')->default(0);
            $table->integer('hours')->default(0);
            $table->string('type', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core.subjects');
    }
};
