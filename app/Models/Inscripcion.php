<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $fillable = ['usuario_id','grupo_id','puntos_obtenidos'];
    public function usuario():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function grupo():BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }
}
