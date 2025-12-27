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
        Schema::create('realizacion_retos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('reto_id')->references('id')->on('retos')->onDelete('cascade');
            $table->float('calificacion')->nullable();
            $table->integer('no_intentos')->default(0);
            $table->dateTime('fecha_realizacion')->useCurrent();
            $table->json('respuesta')->nullable();
            $table->boolean('calificado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realizacion_retos');
    }
};
