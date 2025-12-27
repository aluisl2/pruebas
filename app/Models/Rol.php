<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $clave
 * @property string $nombre
 * @property string $descripcion
 */
class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['clave','nombre','descripcion'];

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
