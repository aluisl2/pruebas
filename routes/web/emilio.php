<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AlumnoGrupoController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth'])->group(function () {

    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumno.index');

    //Entrar a un grupo
    Route::post('/alumnos/grupos/unirse', [AlumnoGrupoController::class, 'join'])
        ->name('alumno.grupos.join');

    // HUB de grupos (cards)
    Route::get('/alumnos/grupos', [AlumnoGrupoController::class, 'index'])
        ->name('alumno.grupos.index');

    // Detalle por grupo (puedes dejarlo en AlumnoController)
    Route::get('/alumnos/grupos/{grupo}/retos', [AlumnoController::class, 'retos'])
        ->name('alumno.grupo.retos');

    Route::get('/alumnos/grupos/{grupo}/miembros', [AlumnoController::class, 'miembros'])
        ->name('alumno.grupo.miembros');

});


Route::get('profesor/miembros',[\App\Http\Controllers\ProfesorController::class,'view'])->name('profesor.miembros');
Route::get('profesor/retos',[\App\Http\Controllers\ProfesorController::class,'index'])->name('profesor.retos');
Route::get('welcome',[WelcomeController::class,'index'])->name('welcome');
