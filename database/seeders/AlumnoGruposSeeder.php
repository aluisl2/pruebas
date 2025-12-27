<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlumnoGruposSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1) Roles (si no existen)
            $rolProfesorId = DB::table('roles')->where('clave', 'PROFESOR')->value('id');
            if (!$rolProfesorId) {
                $rolProfesorId = DB::table('roles')->insertGetId([
                    'clave' => 'PROFESOR',
                    'nombre' => 'Profesor',
                    'descripcion' => 'Rol de profesor',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $rolAlumnoId = DB::table('roles')->where('clave', 'ALUMNO')->value('id');
            if (!$rolAlumnoId) {
                $rolAlumnoId = DB::table('roles')->insertGetId([
                    'clave' => 'ALUMNO',
                    'nombre' => 'Alumno',
                    'descripcion' => 'Rol de alumno',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2) Usuario profesor (si no existe)
            $profEmail = 'profe@bitlab.test';
            $profId = DB::table('users')->where('email', $profEmail)->value('id');

            if (!$profId) {
                $profId = DB::table('users')->insertGetId([
                    'rol_id' => $rolProfesorId,
                    'nombre' => 'Ana',
                    'apellido_paterno' => 'García',
                    'apellido_materno' => 'López',
                    'matricula' => 900000, // único
                    'email' => $profEmail,
                    'estado' => 1,
                    'password' => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3) Grupos (3 grupos) creados por el profesor
            $gruposData = [
                [
                    'clave' => 'GPO-ISC-01',
                    'nombre' => 'Algoritmos - Grupo 01',
                    'portada' => 'portadas/grupo01.jpg',
                    'descripcion' => 'Grupo de práctica de algoritmos.',
                ],
                [
                    'clave' => 'GPO-ISC-02',
                    'nombre' => 'Bases de Datos - Grupo 02',
                    'portada' => 'portadas/grupo02.jpg',
                    'descripcion' => 'Grupo para retos de SQL y modelado.',
                ],
                [
                    'clave' => 'GPO-ISC-03',
                    'nombre' => 'Redes - Grupo 03',
                    'portada' => 'portadas/grupo03.jpg',
                    'descripcion' => 'Grupo para retos de redes y routing.',
                ],
            ];

            $grupoIds = [];
            foreach ($gruposData as $g) {
                $existingId = DB::table('grupos')->where('clave', $g['clave'])->value('id');
                if ($existingId) {
                    $grupoIds[] = $existingId;
                    continue;
                }

                $grupoIds[] = DB::table('grupos')->insertGetId([
                    'usuario_id' => $profId,
                    'clave' => $g['clave'],
                    'nombre' => $g['nombre'],
                    'portada' => $g['portada'],
                    'descripcion' => $g['descripcion'],
                    'concluido' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4) Crear 10 alumnos (si no existen) y guardamos sus IDs
            $alumnos = [];
            for ($i = 1; $i <= 10; $i++) {
                $matricula = 20250000 + $i; // único
                $email = "alumno{$i}@bitlab.test";

                $id = DB::table('users')->where('email', $email)->value('id');
                if (!$id) {
                    $id = DB::table('users')->insertGetId([
                        'rol_id' => $rolAlumnoId,
                        'nombre' => "Alumno{$i}",
                        'apellido_paterno' => "Paterno{$i}",
                        'apellido_materno' => "Materno{$i}",
                        'matricula' => $matricula,
                        'email' => $email,
                        'estado' => 1,
                        'password' => Hash::make('password'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $alumnos[] = $id;
            }

            // 5) Inscribir a los 10 alumnos repartidos 4-3-3 en los 3 grupos
            //    (inscripciones: usuario_id, grupo_id, puntos_obtenidos)
            $reparto = [
                $grupoIds[0] => array_slice($alumnos, 0, 4),
                $grupoIds[1] => array_slice($alumnos, 4, 3),
                $grupoIds[2] => array_slice($alumnos, 7, 3),
            ];

            foreach ($reparto as $grupoId => $alumnoIds) {
                foreach ($alumnoIds as $alumnoId) {

                    $yaExiste = DB::table('inscripciones')
                        ->where('grupo_id', $grupoId)
                        ->where('usuario_id', $alumnoId)
                        ->exists();

                    if ($yaExiste) continue;

                    DB::table('inscripciones')->insert([
                        'usuario_id' => $alumnoId,
                        'grupo_id' => $grupoId,
                        'puntos_obtenidos' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}
