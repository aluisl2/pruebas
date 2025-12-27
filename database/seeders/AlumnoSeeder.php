<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;
use App\Models\Grupo;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos el rol 'Alumno' (Coincide con tu AlumnoGruposSeeder)
        $rolAlumno = Rol::where('nombre', 'ALUMNO')->first();

        if (!$rolAlumno) {
            $this->command->error('❌ No se encontró el rol "Alumno".');
            return;
        }

        // 2. Buscamos un grupo para inscribirlos
        $grupo = Grupo::first();

        if (!$grupo) {
            $this->command->error('❌ No hay grupos. Crea uno primero.');
            return;
        }

        $this->command->info("📝 Inscribiendo alumnos en: " . $grupo->nombre);

        // 3. Crear 10 alumnos
        $nombres = ['Ana', 'Carlos', 'Sofia', 'Miguel', 'Lucia', 'Jorge', 'Elena', 'David', 'Valentina', 'Pedro'];

        foreach ($nombres as $i => $nombre) {
            $matricula = 20250000 + $i;

            $alumno = User::firstOrCreate(
                ['matricula' => $matricula], // Buscamos por matrícula para no duplicar
                [
                    'rol_id' => $rolAlumno->id,
                    'matricula' => $matricula,
                    'nombre' => $nombre,
                    'apellido_paterno' => 'Test',
                    'apellido_materno' => 'Seeder',
                    'email' => strtolower($nombre) . $i . '@alumno.com',
                    'password' => Hash::make('password'),
                    'estado' => true,
                ]
            );

            // 4. Inscribir (evitando duplicados)
            if (!$alumno->gruposInscritos()->where('grupo_id', $grupo->id)->exists()) {
                $alumno->gruposInscritos()->attach($grupo->id, [
                    'puntos_obtenidos' => rand(0, 100)
                ]);
            }
        }

        $this->command->info('✅ Alumnos creados e inscritos.');
    }
}
