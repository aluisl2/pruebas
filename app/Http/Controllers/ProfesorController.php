<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Grupo;


class ProfesorController extends Controller
{
    public function view()
    {
        return Inertia::render('Miembros/ProfesorMiembros');
    }

    public function index()
    {
        return Inertia::render('Retos/ProfesorRetos');
    }

    public function eliminarMiembro(Request $request, $grupoId, $alumnoId)
    {
        $grupo = Grupo::findOrFail($grupoId);

        // Seguridad: Verificar que el grupo pertenece al profesor autenticado
        if ($grupo->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso.');
        }

        // Expulsar al alumno (detach)
        $grupo->alumnos()->detach($alumnoId);

        return back()->with('success', 'Alumno eliminado correctamente.');
    }
}
