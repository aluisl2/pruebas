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
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso total al sistema'
            ],
            [
                'clave' => 'PROFESOR',
                'nombre' => 'Profesor',
                'descripcion' => 'Puede crear grupos y retos'
            ],
            [
                'clave' => 'ALUMNO',
                'nombre' => 'Estudiante',
                'descripcion' => 'Puede inscribirse y resolver retos'
            ]
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
