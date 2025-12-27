<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\RealizacionReto;
use App\Models\Reto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
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
}
