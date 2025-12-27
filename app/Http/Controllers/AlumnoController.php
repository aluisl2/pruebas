<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumnoController extends Controller
{
    public function index()
    {
        return redirect()->route('alumno.grupos.index');
    }

    public function retos(Request $request, Grupo $grupo)
    {
        $user = $request->user();

        // seguridad: validar que el alumno pertenece al grupo
        abort_unless(
            $user->gruposInscritos()->where('grupos.id', $grupo->id)->exists(),
            403
        );

        // sidebar: grupos del alumno
        $groups = $user->gruposInscritos()
            ->select('grupos.id', 'grupos.nombre', 'grupos.clave')
            ->orderBy('grupos.nombre')
            ->get();

        // puntos del alumno en ESTE grupo (desde pivot)
        $pivot = $user->gruposInscritos()
            ->where('grupos.id', $grupo->id)
            ->first()?->pivot;

        $totalPoints = (int) ($pivot?->puntos_obtenidos ?? 0);

        // retos del grupo + realización del usuario
        $retos = $grupo->retos()
            ->select('id', 'grupo_id', 'titulo', 'puntaje', 'max_intentos', 'fecha_limite')
            ->with(['realizaciones' => function ($q) use ($user) {
                $q->where('usuario_id', $user->id)
                    ->select('id', 'reto_id', 'usuario_id', 'calificacion', 'no_intentos', 'fecha_realizacion', 'calificado');
            }])
            ->orderBy('fecha_limite')
            ->get()
            ->map(function ($reto) {
                $real = $reto->realizaciones->first();

                return [
                    'id' => $reto->id,
                    // puedes mandar doble (para que Vue no sufra)
                    'titulo' => $reto->titulo,
                    'title'  => $reto->titulo,

                    'progreso' => ($real?->no_intentos ?? 0) . '/' . ($reto->max_intentos ?? 0),
                    'progress' => ($real?->no_intentos ?? 0) . '/' . ($reto->max_intentos ?? 0),

                    'puntos' => $reto->puntaje . ' pts',
                    'points' => $reto->puntaje . ' pts',

                    'estado' => $real
                        ? ($real->calificado ? (($real->calificacion ?? 0) . '%') : 'Pendiente')
                        : 'Pendiente',
                    'status' => $real
                        ? ($real->calificado ? (($real->calificacion ?? 0) . '%') : 'Pendiente')
                        : 'Pendiente',
                ];
            });

        return Inertia::render('Retos/AlumnosRetos', [
            'groups' => $groups,
            'activeGroupId' => $grupo->id,

            'group' => [
                'id' => $grupo->id,
                'nombre' => $grupo->nombre,
                'clave' => $grupo->clave,
                'created_at' => optional($grupo->created_at)->format('d/m/Y'),
            ],

            'retos' => $retos,

            // header alumno
            'studentName' => trim($user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno),
            'totalPoints' => $totalPoints,
            'avgPercent' => 0, // tu cálculo real

            // tabs href
            'hrefRetos' => route('alumno.grupo.retos', $grupo->id),
            'hrefMiembros' => route('alumno.grupo.miembros', $grupo->id),
        ]);
    }

    public function miembros(Request $request, Grupo $grupo)
    {
        $user = $request->user();

        abort_unless(
            $user->gruposInscritos()->where('grupos.id', $grupo->id)->exists(),
            403
        );

        $groups = $user->gruposInscritos()
            ->select('grupos.id', 'grupos.nombre', 'grupos.clave')
            ->orderBy('grupos.nombre')
            ->get();

        $pivot = $user->gruposInscritos()
            ->where('grupos.id', $grupo->id)
            ->first()?->pivot;

        $totalPoints = (int) ($pivot?->puntos_obtenidos ?? 0);

        $miembros = $grupo->alumnos()
            ->select('users.id', 'users.nombre', 'users.apellido_paterno', 'users.apellido_materno')
            ->withPivot('puntos_obtenidos')
            ->orderBy('users.nombre')
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'nombre' => trim($u->nombre.' '.$u->apellido_paterno.' '.$u->apellido_materno),
                'name' => trim($u->nombre.' '.$u->apellido_paterno.' '.$u->apellido_materno),

                // si luego quieres porcentaje, aquí lo cambias
                'percent' => '0%',
                'points' => (int) ($u->pivot?->puntos_obtenidos ?? 0),
            ]);

        return Inertia::render('Miembros/AlumnoMiembros', [
            'groups' => $groups,
            'activeGroupId' => $grupo->id,

            'group' => [
                'id' => $grupo->id,
                'nombre' => $grupo->nombre,
                'clave' => $grupo->clave,
                'created_at' => optional($grupo->created_at)->format('d/m/Y'),
            ],

            'miembros' => $miembros,

            // header alumno
            'studentName' => trim($user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno),
            'totalPoints' => $totalPoints,
            'avgPercent' => 0,

            // tabs href
            'hrefRetos' => route('alumno.grupo.retos', $grupo->id),
            'hrefMiembros' => route('alumno.grupo.miembros', $grupo->id),
        ]);
    }
}
