<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_ingreso.payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('core.students')->restrictOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('app_gestion_ingreso.enrollments')->nullOnDelete();
            $table->string('concept', 150);
            $table->decimal('amount', 10, 2);
            $table->string('status', 20)->default('pendiente');
            $table->string('receipt_number', 50)->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_ingreso.payment_orders');
    }
};
