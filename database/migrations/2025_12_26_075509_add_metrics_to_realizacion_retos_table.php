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
            $table->integer('aciertos')->default(0)->after('calificacion');
            $table->integer('total_reactivos')->default(0)->after('aciertos');
            $table->boolean('es_mejor_intento')->default(false)->after('total_reactivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realizacion_retos', function (Blueprint $table) {
            $table->dropColumn(['aciertos', 'total_reactivos', 'es_mejor_intento']);
        });
    }
};