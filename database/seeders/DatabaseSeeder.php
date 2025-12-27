<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $rolProfesor = Rol::where('nombre', 'Profesor')->first();
        User::query()->create([
            'rol_id' => $rolAdmin->id,
            'matricula' => '12345678',
            'nombre' => 'admin',
            'apellido_materno' => 'admin',
            'apellido_paterno' => 'admin',
            'email' => 'admin@bitlab.com',
            'password' => bcrypt('12345'),
        ]);
        User::query()->create([
            'rol_id' => $rolProfesor->id,
            'matricula' => '123456789',
            'nombre' => 'Angel',
            'apellido_materno' => 'Luis',
            'apellido_paterno' => 'Luis',
            'email'=> 'angeltest@bitlab.com',
            'password' => bcrypt('12345'),
        ]);
        $this->call([
            GrupoSeeder::class,
        ]);
    }
}
