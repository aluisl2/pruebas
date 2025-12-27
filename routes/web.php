<?php

use App\Http\Controllers\GrupoController;
use App\Http\Controllers\GrupoManageController;
use App\Http\Middleware\GrupoMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RetoController;
use App\Http\Controllers\FeedbackController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // grupos
    Route::middleware(GrupoMiddleware::class)
        ->group(function () {
            Route::get('dashboard', GrupoController::class)
                ->name('dashboard');
            Route::get('grupo/{id}',[GrupoController::class,'show'])
                ->name('grupo.show');
           // Route::post('profesor/{id}/alumnos',[GrupoManageController::class,'getMembers']);
        });
    Route::get('/grupos/crear', [GrupoController::class, 'create'])
        ->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])
        ->name('grupos.store');
    Route::post('grupos/inscribir',[GrupoController::class,'inscribir']);
    Route::post('/grupo/{id}/miembros',[GrupoController::class,'getMembers']);
    Route::post('/grupo/{grupoId}/miembro/{userId}', [GrupoController::class, 'getMember']);
    Route::post('/grupo/{id}/retos',[GrupoController::class,'getRetos']);
    // retos
    Route::get('/retos/{id}/crear', [RetoController::class, 'create'])
        ->name('retos.create');
    Route::post('/retos', [RetoController::class, 'store'])->name('retos.store');
    Route::get('/reto/{id}',[RetoController::class,'show']);
    Route::inertia('/construction', 'Construction')->name('construction');
    Route::post('/reto/guardar/realizacion',[RetoController::class,'guardarRealizacionReto']);
    
    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    require "web/emilio.php";
    require "web/angel.php";
});
