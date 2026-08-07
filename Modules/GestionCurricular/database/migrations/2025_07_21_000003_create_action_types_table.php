<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.action_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 30)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.action_types');
    }
};
