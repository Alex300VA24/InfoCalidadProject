<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technical_report_id')->constrained('app_gestion_curricular.technical_reports')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('core.users')->restrictOnDelete();
            $table->string('decision', 20);
            $table->text('comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.approvals');
    }
};
