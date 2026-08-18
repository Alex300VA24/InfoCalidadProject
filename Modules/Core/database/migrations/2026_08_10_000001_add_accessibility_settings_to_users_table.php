<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('core.users', function (Blueprint $table) {
            $table->unsignedSmallInteger('text_scale')
                ->default(100)
                ->after('is_active');

            $table->unsignedSmallInteger('view_scale')
                ->default(100)
                ->after('text_scale');
        });
    }

    public function down(): void
    {
        Schema::table('core.users', function (Blueprint $table) {
            $table->dropColumn(['text_scale', 'view_scale']);
        });
    }
};
