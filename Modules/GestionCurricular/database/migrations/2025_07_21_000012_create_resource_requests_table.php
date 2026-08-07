<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.resource_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('academic_period_id')->constrained('core.academic_periods')->restrictOnDelete();
            $table->foreignId('applicant_id')->constrained('core.users')->restrictOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('request_type', 50);
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.resource_requests');
    }
};
