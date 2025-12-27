<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reto extends Model
{
    protected $table = 'retos';
    protected $fillable = [
        'grupo_id',
        'titulo',
        'descripcion',
        'puntaje',
        'opciones',
        'max_intentos',
        'tiempo_limite',
        'ayuda',
        'fecha_limite',
    ];
    protected $casts = [
        'opciones' => 'array',
        'fecha_limite' => 'datetime:d/m/Y H:i',
    ];
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }
    public function realizaciones(): HasMany
    {
        return $this->hasMany(RealizacionReto::class);
    }
}
