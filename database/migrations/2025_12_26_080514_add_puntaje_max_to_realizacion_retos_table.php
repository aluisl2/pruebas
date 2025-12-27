<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('realizacion_retos', function (Blueprint $table) {
            $table->double('puntaje_max')->nullable()->after('calificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realizacion_retos', function (Blueprint $table) {
            $table->dropColumn('puntaje_max');
        });
    }
};