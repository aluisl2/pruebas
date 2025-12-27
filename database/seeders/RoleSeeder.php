<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'clave' => 'ADMIN',
                'nombre' => 'ADMIN',
                'descripcion' => 'Acceso total al sistema'
            ],
            [
                'clave' => 'PROFESOR',
                'nombre' => 'PROFESOR',
                'descripcion' => 'Puede crear grupos y retos'
            ],
            [
                'clave' => 'ALUMNO',
                'nombre' => 'ALUMNO',
                'descripcion' => 'Puede inscribirse y resolver retos'
            ]
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
