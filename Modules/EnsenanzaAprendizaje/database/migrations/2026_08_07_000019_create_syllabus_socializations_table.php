<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ensenanza_aprendizaje.syllabus_socializations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_id')->constrained('app_gestion_curricular.syllabi')->cascadeOnDelete();
            $table->date('date');
            $table->string('evidence_path', 500)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('core.users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ensenanza_aprendizaje.syllabus_socializations');
    }
};
