<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rol_id',
        'matricula',
        'clave',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'activo',
    ];

    protected $with = [
        'rol'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];



    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];



    /**
     * Get the attributes that should be cast.
     *
     */
    public function rol(){
        return $this->belongsTo(Rol::class);
    }

    public function grupos(){
        return $this->belongsToMany(
            Grupo::class,
            'inscripciones',
            'usuario_id',
            'grupo_id'
        );
    }
    public function grupos_inscritos(){
        return $this->belongsToMany(Grupo::class, 'inscripciones', 'usuario_id', 'grupo_id')
            ->withPivot('puntos_obtenidos','id')
            ->withTimestamps();
    }

    public function gruposInscritos()
    {
        return $this->grupos_inscritos();
    }

    public function gruposImpartidos()
    {
        return $this->hasMany(Grupo::class, 'usuario_id');
    }

    public function realizaciones(){
        return $this->hasMany(RealizacionReto::class);
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
