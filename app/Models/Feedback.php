<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'comentarios',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}