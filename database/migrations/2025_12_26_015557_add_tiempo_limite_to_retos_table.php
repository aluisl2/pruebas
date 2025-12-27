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
        Schema::table('retos', function (Blueprint $table) {
            $table->integer('tiempo_limite')->default(0)->after('max_intentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->dropColumn('tiempo_limite');
        });
    }
};