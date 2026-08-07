<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_gestion_curricular.resource_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_request_id')->constrained('app_gestion_curricular.resource_requests')->cascadeOnDelete();
            $table->string('document_type', 20);
            $table->string('document_number', 50)->nullable();
            $table->string('subject', 255)->nullable();
            $table->string('filename', 255);
            $table->string('file_path', 500);
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_gestion_curricular.resource_documents');
    }
};
