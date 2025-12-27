<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolProfe =Rol::where('nombre','Profesor')->first();
        $profesor =User::where('rol_id',$rolProfe->id)->first();
        $grupos = [
            [
                'clave' => 10101,
                'nombre' => 'Programacion Orientada a Objetos',
                'descripcion' => 'Conceptos fundamentales de POO con Java y PHP.',
                'portada' => 'https://plus.unsplash.com/premium_photo-1664304160128-ca5a08ac46ce?q=80&w=1153&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'concluido' => false,
            ],
            [
                'clave' => 20202,
                'nombre' => 'Estructura de Datos',
                'descripcion' => 'Listas, pilas, colas y árboles binarios.',
                'portada' => 'https://images.unsplash.com/photo-1489875347897-49f64b51c1f8?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'concluido' => false,
            ],
            [
                'clave' => 30303,
                'nombre' => 'Bases de Datos Relacionales',
                'descripcion' => 'Diseño ER, Normalización y SQL avanzado.',
                'portada' => 'https://images.unsplash.com/photo-1667372283496-893f0b1e7c16?q=80&w=1632&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'concluido' => true,
            ],
            [
                'clave' => 40404,
                'nombre' => 'Desarrollo Web Frontend',
                'descripcion' => 'Tailwind CSS y Vite.',
                'portada' => 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'concluido' => false,
            ],
        ];
        foreach ($grupos as $datos) {
            Grupo::firstOrCreate(
                ['clave' => $datos['clave']],
                [
                    'usuario_id' => $profesor->id,
                    'nombre' => $datos['nombre'],
                    'descripcion' => $datos['descripcion'],
                    'portada' => $datos['portada'],
                    'concluido' => $datos['concluido']
                ]
            );
        }
    }
}
