<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Acta de Notas</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #111827; }
        .header { text-align: center; border-bottom: 3px solid #1a237e; padding-bottom: 8px; }
        .header h1 { margin: 0; font-size: 15px; color: #1a237e; }
        .header p { margin: 2px 0; font-size: 10px; color: #4b5563; }
        .title { text-align: center; font-size: 12px; font-weight: bold; margin: 18px 0; text-transform: uppercase; }
        table.acta { width: 100%; border-collapse: collapse; }
        table.acta th, table.acta td { border: 1px solid #cbd5e1; padding: 5px 8px; text-align: center; }
        table.acta th { background: #1a237e; color: #fff; font-size: 9px; text-transform: uppercase; }
        table.acta td.student { text-align: left; font-weight: bold; }
        .legend { margin-top: 12px; font-size: 9px; color: #4b5563; }
        .signatures { margin-top: 55px; }
        .sig { width: 40%; display: inline-block; text-align: center; }
        .sig .line { border-top: 1px solid #111827; padding-top: 4px; font-weight: bold; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Universidad Nacional de Trujillo</h1>
        <p>{{ $subject->career?->name ?? 'Escuela Profesional' }} · Dirección de Registro Técnico</p>
    </div>

    <div class="title">Acta de Evaluación</div>

    <p style="font-weight:bold;">Asignatura: {{ $subject->code }} - {{ $subject->name }}</p>
    <p>Periodo Académico: {{ $period->name }}</p>

    <table class="acta">
        <thead>
            <tr>
                <th>N°</th>
                <th>Código</th>
                <th class="student">Estudiante</th>
                <th>P1</th>
                <th>P2</th>
                <th>P3</th>
                <th>Parcial</th>
                <th>Final</th>
                <th>Promedio</th>
                <th>Condición</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['student']->codigo }}</td>
                    <td class="student">{{ $row['student']->fullName() }}</td>
                    <td>{{ $row['p1'] ?? '—' }}</td>
                    <td>{{ $row['p2'] ?? '—' }}</td>
                    <td>{{ $row['p3'] ?? '—' }}</td>
                    <td>{{ $row['parcial'] ?? '—' }}</td>
                    <td>{{ $row['final'] ?? '—' }}</td>
                    <td style="font-weight:bold;">{{ $row['promedio'] ?? '—' }}</td>
                    <td>{{ $row['promedio'] === null ? '' : ($row['promedio'] >= 14 ? 'APROBADO' : ($row['promedio'] >= 10 ? 'EN RIESGO' : 'DESAPROBADO')) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="legend">Escala vigesimal: 0 - 20. Aprobado ≥ 14. P1-P3: prácticas (10% c/u), Parcial: 30%, Final: 40%.</p>

    <div class="signatures">
        <span class="sig"><div class="line">Docente del curso</div></span>
        <span class="sig"><div class="line">Secretaría Académica</div></span>
    </div>
</body>
</html>
