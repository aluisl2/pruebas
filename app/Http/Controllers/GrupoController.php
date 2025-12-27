<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GrupoController extends Controller
{
    public function __invoke(Request $request){
        /** @var User $user */
        $user = Auth::user();
        // dependiendo el ROL


        return Inertia::render('GruposDashboard');
    }

    public function create()
    {
        return Inertia::render('Grupos/CrearGrupos');
    }

    public function inscribir(Request $request){
        ['codigo' => $clave] = $request->validate([
            'codigo' => 'required|exists:grupos,clave',
        ]);
        // buscamos el grupo
        $grupo = Grupo::query()->where('clave', $clave)
            ->first();
        /** @var User $user */
        $user = Auth::user();
        $user->grupos()->attach($grupo);
        return response()->json($grupo);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        do {
            $claveGenerada = mt_rand(10000, 99999);
        } while (Grupo::where('clave', $claveGenerada)->exists());

        $rutaPortada = null;
        $colorAleatorio = null;

        if ($request->hasFile('portada')) {
            $rutaPortada = Storage::disk('public')->putFile('grupos', $request->file('portada'));
        } else {
            $colores = [
                '#F97316', '#3B82F6', '#10B981', '#8B5CF6', '#EF4444', '#EC4899'
            ];
            $colorAleatorio = $colores[array_rand($colores)];
        }

        $request->user()->grupos()->create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'clave' => $claveGenerada,
            'concluido' => false,
            'usuario_id' => Auth::id(),
            'portada' => $rutaPortada,
            'color' => $colorAleatorio
        ]);
        return redirect()->route('dashboard');
    }

    function show($id){
        // mostramos los datos
        $grupo = Grupo::query()->findOrFail($id);
        return Inertia::render('GrupoManage', ['grupo' => $grupo]);
    }

    public function getMembers(Request $request, $id){
        $grupo = Grupo::findOrFail($id);

        // 1. Puntos totales posibles del grupo (suma de puntajes de los retos)
        $retos = $grupo->retos()->get(); // Obtenemos modelos para sumar
        $retosIds = $retos->pluck('id');
        $totalPuntosPosibles = $retos->sum('puntaje');

        /** @var User $user */
        $user = Auth::user();

        // 2. Obtener alumnos inscritos
        $alumnos = User::query()
            ->join('inscripciones', 'users.id', '=', 'inscripciones.usuario_id')
            ->where('inscripciones.grupo_id', $id)
            ->where('users.id', '!=', $user->id) // Excluir al usuario actual (profesor)
            ->where('estado', 1)
            ->select(
                'users.id',
                'users.nombre',
                'users.apellido_paterno',
                'users.apellido_materno',
                'users.matricula',
                'users.email',
                'users.profile_photo_path'
            )
            ->get();

        // 3. Calcular estadísticas por alumno
        $alumnos->transform(function ($alumno) use ($retosIds, $totalPuntosPosibles) {
            // Sumar las calificaciones de los "mejores intentos" en los retos de este grupo
            $puntosObtenidos = \App\Models\RealizacionReto::whereIn('reto_id', $retosIds)
                ->where('usuario_id', $alumno->id)
                ->where('es_mejor_intento', true)
                ->sum('calificacion');

            $porcentaje = $totalPuntosPosibles > 0
                ? ($puntosObtenidos / $totalPuntosPosibles) * 100
                : 0;

            $alumno->puntos_obtenidos = round($puntosObtenidos, 2);
            $alumno->total_puntos_grupo = $totalPuntosPosibles;
            $alumno->porcentaje_avance = round($porcentaje, 1);

            return $alumno;
        });

        return response()->json($alumnos);
    }

    public function getMember($grupoId, $userId){
        $grupo = Grupo::findOrFail($grupoId);

        // 1. Obtener el alumno asegurando que pertenece al grupo
        $alumno = User::whereHas('grupos', function($q) use ($grupoId) {
                $q->where('grupo_id', $grupoId);
            })
            ->select(
                'id',
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'matricula',
                'email',
                'profile_photo_path'
            )
            ->findOrFail($userId);

        // 2. Puntos totales posibles del grupo
        $retos = $grupo->retos()->get();
        $retosIds = $retos->pluck('id');
        $totalPuntosPosibles = $retos->sum('puntaje');

        // 3. Buscar retos realizados
        $realizaciones = \App\Models\RealizacionReto::with('reto')
            ->whereIn('reto_id', $retosIds)
            ->where('usuario_id', $userId)
            ->get();

        // 4. Calcular estadísticas
        $puntosObtenidos = $realizaciones->where('es_mejor_intento', true)->sum('calificacion');

        $porcentaje = $totalPuntosPosibles > 0
            ? ($puntosObtenidos / $totalPuntosPosibles) * 100
            : 0;

        $alumno->puntos_obtenidos = round($puntosObtenidos, 2);
        $alumno->total_puntos_grupo = $totalPuntosPosibles;
        $alumno->porcentaje_avance = round($porcentaje, 1);

        $alumno->realizaciones = $realizaciones;

        return response()->json($alumno);
    }

    public function getRetos($id){
        $grupo = Grupo::query()
            ->findOrFail($id);

        $retos = $grupo
            ->retos()
            ->select('id','titulo','fecha_limite')
            ->get();

        return response()->json($retos);
    }

    //para eliminar la imagen si se borra el grupo
    public function destroy(Grupo $grupo)
    {
        if ($grupo->usuario_id !== Auth::id()) {
            abort(403);
        }

        if ($grupo->portada) {
            if (Storage::disk('public')->exists($grupo->portada)) {
                Storage::disk('public')->delete($grupo->portada);
            }
        }

        $grupo->delete();

        return redirect()->route('dashboard');
    }


}
