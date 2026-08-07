<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha de Matrícula</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111827; }
        .header { text-align: center; border-bottom: 3px solid #1a237e; padding-bottom: 8px; }
        .header h1 { margin: 0; font-size: 16px; color: #1a237e; }
        .header p { margin: 2px 0; font-size: 10px; color: #4b5563; }
        .title { text-align: center; font-size: 13px; font-weight: bold; margin: 20px 0; text-transform: uppercase; }
        .code { text-align: right; font-size: 9px; color: #6b7280; margin-bottom: 4px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.info td { border: 1px solid #cbd5e1; padding: 6px 10px; }
        table.info td.label { width: 180px; background: #f1f5f9; font-weight: bold; }
        table.subjects { width: 100%; border-collapse: collapse; }
        table.subjects th, table.subjects td { border: 1px solid #cbd5e1; padding: 6px 10px; }
        table.subjects th { background: #1a237e; color: #fff; font-size: 10px; text-transform: uppercase; }
        .signatures { margin-top: 60px; }
        .sig { width: 40%; display: inline-block; text-align: center; }
        .sig .line { border-top: 1px solid #111827; padding-top: 4px; font-weight: bold; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Universidad Nacional de Trujillo</h1>
        <p>Unidad de Matrícula · Dirección de Registro Técnico</p>
    </div>

    <p class="code">Formato F3 · {{ $enrollment->code }}</p>
    <div class="title">Ficha de Matrícula</div>

    <table class="info">
        <tr>
            <td class="label">Estudiante</td>
            <td>{{ $enrollment->student?->fullName() }}</td>
            <td class="label">Código</td>
            <td>{{ $enrollment->student?->codigo }}</td>
        </tr>
        <tr>
            <td class="label">Carrera</td>
            <td>{{ $enrollment->career?->name }}</td>
            <td class="label">Periodo</td>
            <td>{{ $enrollment->academicPeriod?->name }}</td>
        </tr>
        <tr>
            <td class="label">Fecha de matrícula</td>
            <td>{{ $enrollment->enrolled_at?->format('d/m/Y') }}</td>
            <td class="label">Estado</td>
            <td>{{ ucfirst($enrollment->status) }}</td>
        </tr>
    </table>

    <table class="subjects">
        <thead>
            <tr>
                <th>N°</th>
                <th>Código</th>
                <th>Asignatura</th>
                <th>Créditos</th>
                <th>Condición</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollment->subjects as $index => $es)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $es->subject?->code }}</td>
                    <td>{{ $es->subject?->name }}</td>
                    <td>{{ $es->subject?->credits }}</td>
                    <td>{{ ucfirst($es->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <span class="sig"><div class="line">Personal de Matrícula</div></span>
        <span class="sig"><div class="line">Estudiante</div></span>
    </div>
</body>
</html>
