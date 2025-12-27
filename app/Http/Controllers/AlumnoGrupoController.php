<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumnoGrupoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $grupos = $user->gruposInscritos()
            ->select('grupos.id', 'grupos.nombre', 'grupos.clave', 'grupos.created_at')
            ->orderBy('grupos.nombre')
            ->get()
            ->map(fn($g) => [
                'id' => $g->id,
                'nombre' => $g->nombre,
                'clave' => $g->clave,
                'fecha' => optional($g->created_at)->format('d/m/Y'),
            ]);

        return Inertia::render('GruposDashboard', [
            'grupos' => $grupos,
        ]);
    }

        public function join(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'codigo' => ['required','string','max:50'],
        ]);

        // Normaliza (por si meten espacios)
        $codigo = trim($validated['codigo']);

        // Busca el grupo por clave
        $grupo = Grupo::query()
            ->where('clave', $codigo)
            ->first();

        if (!$grupo) {
            return back()->withErrors([
                'codigo' => 'Ese código no existe. Verifica con tu profesor.',
            ]);
        }

        // Evita duplicar inscripción
        $yaInscrito = $user->gruposInscritos()
            ->where('grupos.id', $grupo->id)
            ->exists();

        if ($yaInscrito) {
            return back()->withErrors([
                'codigo' => 'Ya estás inscrito en ese grupo.',
            ]);
        }

        // Inserta en tabla pivote (inscripciones)
        // OJO: en tu relación belongsToMany ya usa 'inscripciones'
        $user->gruposInscritos()->attach($grupo->id, [
            'puntos_obtenidos' => 0,
        ]);

        // Lo más útil: mandarlo directo al grupo recién unido
        return redirect()->route('alumno.grupo.retos', $grupo->id);
    }
}
