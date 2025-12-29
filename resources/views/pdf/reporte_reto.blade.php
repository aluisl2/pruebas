<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte {{ $reto->titulo }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #E17101; padding-bottom: 10px; }
        .summary { width: 100%; margin-bottom: 20px; }
        .summary td { text-align: center; background: #f4f4f4; padding: 10px; border: 1px solid #ddd; }
        .label { font-size: 10px; text-transform: uppercase; color: #666; }
        .value { font-size: 18px; font-weight: bold; color: #E17101; }
        table.details { width: 100%; border-collapse: collapse; }
        table.details th { background: #2B2E36; color: white; padding: 8px; text-align: left; font-size: 12px; }
        table.details td { border-bottom: 1px solid #ddd; padding: 8px; font-size: 12px; }
        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="header">
    <h2>{{ $reto->titulo }}</h2>
    <p>Grupo: {{ $reto->grupo->nombre }} | Generado: {{ $fecha }}</p>
</div>

@if($stats)
    <table class="summary">
        <tr>
            <td>
                <div class="value">{{ $stats['promedio_calificacion'] }}</div>
                <div class="label">Promedio</div>
            </td>
            <td>
                <div class="value">{{ $stats['promedio_tiempo'] }}</div>
                <div class="label">Tiempo Promedio</div>
            </td>
            <td>
                <div class="value">{{ $stats['aprobados'] }} / {{ $stats['total_alumnos'] }}</div>
                <div class="label">Aprobados</div>
            </td>
        </tr>
    </table>

    <table class="details">
        <thead>
        <tr>
            <th>Alumno</th>
            <th>Matrícula</th>
            <th>Tiempo</th>
            <th>Calif.</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stats['detalles'] as $d)
            <tr>
                <td>{{ $d->user->nombre }} {{ $d->user->apellido_paterno }}</td>
                <td>{{ $d->user->matricula }}</td>
                <td>{{ $d->tiempo_tomado }}</td>
                <td class="{{ $d->calificacion >= 70 ? 'pass' : 'fail' }}">
                    {{ $d->calificacion }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p style="text-align:center">Sin datos registrados.</p>
@endif
</body>
</html>
