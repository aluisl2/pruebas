<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\RealizacionReto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;

class RetoController extends Controller
{
    public function create($id)
    {
        /** @var Grupo $grupo */
        $grupo = Grupo::findOrFail($id);
        return Inertia::render('RetoCrear', ['grupo' => $grupo]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'puntaje' => 'required|integer',
            'opciones' => 'required|array',
            'opciones.*.texto' => 'required|string',
            'opciones.*.tipo' => 'required|in:libre,multiple',
            'opciones.*.alternativas' => 'nullable|array',
            'fecha_limite' => 'required|date',
            'max_intentos' => 'required|integer',
            'tiempo_limite' => 'required|integer|min:3',
            'ayuda' => 'nullable|string',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        /** @var Grupo $grupo */
        $grupo = Grupo::findOrFail($validated['grupo_id']);
        // Creamos el reto
        $grupo->retos()->create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'puntaje' => $validated['puntaje'],
            'opciones' => $validated['opciones'],
            'max_intentos' => $validated['max_intentos'],
            'tiempo_limite' => $validated['tiempo_limite'],
            'ayuda' => $validated['ayuda'],
            'fecha_limite' => Carbon::parse($validated['fecha_limite']),
        ]);

        return Redirect::route('grupo.show', [$grupo->id])
            ->with('success', 'Reto creado correctamente');
    }


        public function show($id){
            $reto = Reto::findOrFail($id);
            $intentosPreviosData = RealizacionReto::where('usuario_id', auth()->id())
                ->where('reto_id', $id)
                ->get();
                    $intentosPrevios = $intentosPreviosData->count();
                    $mejorCalificacion = $intentosPreviosData->max('calificacion') ?? 0;
                    $yaTerminado = $intentosPreviosData->where('calificacion', '>=', $reto->puntaje)->isNotEmpty();
                    return Inertia::render('RetoShow',[
                        'reto'=>$reto,
                        'intentos_previos' => $intentosPrevios,
                        'mejor_calificacion' => $mejorCalificacion,
                        'ya_terminado' => $yaTerminado
                    ]);
        }

    public function guardarRealizacionReto(Request $request)
    {
        $validated = $request->validate([
            'reto_id' => 'required|exists:retos,id',
            'aciertos' => 'required|integer|min:0',
            'respuestas' => 'required|array',
        ]);

        $reto = Reto::findOrFail($validated['reto_id']);
        $userId = auth()->id();

        // 1. Obtener intentos previos para calcular el número de intento actual
        $intentosPrevios = RealizacionReto::query()
            ->where('usuario_id', $userId)
            ->where('reto_id', $reto->id)
            ->get();

        $numeroIntentoActual = $intentosPrevios->count() + 1;

        // 2. Calcular calificación
        $totalReactivos = count($reto->opciones ?? []);
        $calificacion = 0;
        if ($totalReactivos > 0)
            $calificacion = ($validated['aciertos'] / $totalReactivos) * $reto->puntaje;


        // 3. Determinar si es el mejor intento (Estrictamente mayor que el máximo anterior)
        $maxCalificacionAnterior = $intentosPrevios->max('calificacion') ?? 0;

        // Si no hay intentos previos, este es el mejor por defecto
        if ($intentosPrevios->isEmpty()) {
            $esMejorIntento = true;
        } else {
            $esMejorIntento = $calificacion > $maxCalificacionAnterior;
        }

        if ($esMejorIntento) {
            // quitamos al anterior que es mejor reto
            RealizacionReto::where('usuario_id', $userId)
                ->where('reto_id', $validated['reto_id'])
                ->where('es_mejor_intento', true)
                ->update(['es_mejor_intento' => false]);
        }

                RealizacionReto::create([

                    'usuario_id' => $userId,

                    'reto_id' => $validated['reto_id'],

                    'calificacion' => $calificacion,

                    'puntaje_max' => $reto->puntaje,

                    'aciertos' => $validated['aciertos'],

                    'total_reactivos' => $totalReactivos,
            'es_mejor_intento' => $esMejorIntento,
            'no_intentos' => $numeroIntentoActual,
            'fecha_realizacion' => Carbon::now(),
            'respuesta' => $validated['respuestas'],
            'calificado' => true,
        ]);

        return back()->with('success', 'Reto guardado correctamente');
    }
    private function calcularEstadisticas($retoId)
    {
        $realizaciones = RealizacionReto::with('user')
            ->where('reto_id', $retoId)
            ->where('es_mejor_intento', true) // Analizamos solo el mejor intento de cada alumno
            ->get();

        if ($realizaciones->isEmpty()) {
            return null;
        }

        // 1. Promedio de Calificación
        $promedioCalificacion = $realizaciones->avg('calificacion');

        // 2. Promedio de Tiempo (Asumiendo que guardas el tiempo en segundos o tienes que convertirlo)
        // Si 'tiempo_tomado' es un string "00:05:30", hay que convertir a segundos primero.
        // Aquí asumo que podrías estar guardando segundos o TIME.
        // Lógica genérica para convertir Time string a Segundos:
        $totalSegundos = $realizaciones->map(function ($r) {
            // Ajusta esto según cómo guardes el tiempo en tu BD.
            // Si es columna TIME (H:M:S):
            return Carbon::parse($r->tiempo_tomado)->secondsSinceMidnight();
        })->avg();

        // Formatear promedio de tiempo a H:i:s
        $promedioTiempo = gmdate('H:i:s', (int)$totalSegundos);

        // 3. Tasas
        $totalAlumnos = $realizaciones->count();
        $aprobados = $realizaciones->where('calificacion', '>=', 6)->count(); // Umbral ejemplo 70
        $reprobados = $totalAlumnos - $aprobados;

        return [
            'total_alumnos' => $totalAlumnos,
            'promedio_calificacion' => round($promedioCalificacion, 2),
            'promedio_tiempo' => $promedioTiempo,
            'aprobados' => $aprobados,
            'reprobados' => $reprobados,
            'detalle_alumnos' => $realizaciones // Lista completa para la tabla
        ];
    }
    public function reporte($id)
    {
        $reto = Reto::with('grupo')->findOrFail($id);

        if ($reto->grupo->usuario_id !== Auth::id()) {
            abort(403);
        }
        /** @var User $user */
        $user = Auth::user();
        $gruposCreados = Grupo::where('usuario_id', $user->id)->orderBy('created_at', 'desc')->get();
        $gruposInscritos = $user->grupos()->orderBy('created_at', 'desc')->get();

        // Fusionamos para el sidebar
        $grupos = $gruposCreados->merge($gruposInscritos)->unique('id')->values();

        $estadisticas = $this->calcularEstadisticas($id);

        return Inertia::render('Retos/ReporteReto', [
            'reto' => $reto,
            'stats' => $estadisticas,
            'grupos' => $grupos
        ]);
    }
    public function descargarPdf($id)
    {
        $reto = Reto::with('grupo')->findOrFail($id);

        if ($reto->grupo->usuario_id !== Auth::id()) {
            abort(403);
        }

        $estadisticas = $this->calcularEstadisticas($id);

        // Renderizamos una vista Blade para el PDF (wkhtmltopdf usa HTML estático)
        $pdf = SnappyPdf::loadView('pdf.reporte_reto', [
            'reto' => $reto,
            'stats' => $estadisticas,
            'fecha' => now()->format('d-m-Y')
        ]);

        return $pdf->download('reporte_reto_{$reto->clave}.pdf');
    }
}
