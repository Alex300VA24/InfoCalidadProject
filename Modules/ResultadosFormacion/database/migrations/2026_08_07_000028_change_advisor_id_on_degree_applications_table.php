<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->dropColumn('advisor_id');
        });

        Schema::table('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->foreignId('advisor_id')
                ->nullable()
                ->after('thesis_title')
                ->constrained('core.users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('advisor_id');
        });

        Schema::table('app_resultados_formacion.degree_applications', function (Blueprint $table) {
            $table->string('advisor_id')->nullable();
        });
    }
};
