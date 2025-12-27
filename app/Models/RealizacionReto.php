<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealizacionReto extends Model
{
    protected $table = 'realizacion_retos';
    protected $fillable = [
        'usuario_id',
        'reto_id',
        'calificacion',
        'puntaje_max',
        'aciertos',
        'total_reactivos',
        'es_mejor_intento',
        'no_intentos',
        'fecha_realizacion',
        'respuesta',
        'calificado',
    ];
    protected $casts = [
        'respuesta' => 'array',
        'calificado'=>'boolean',
        'es_mejor_intento' => 'boolean',
        'fecha_realizacion'=>'datetime',
    ];
    public function usuario():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function reto():BelongsTo
    {
        return $this->belongsTo(Reto::class);
    }
}
