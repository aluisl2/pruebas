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
        Schema::create('retos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->string('titulo');
            $table->longText('descripcion');
            $table->integer('puntaje');
            $table->json('opciones')->nullable();
            $table->integer('max_intentos')->default(1);
            $table->text('ayuda')->nullable();
            $table->datetime('fecha_limite')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retos');
    }
};
