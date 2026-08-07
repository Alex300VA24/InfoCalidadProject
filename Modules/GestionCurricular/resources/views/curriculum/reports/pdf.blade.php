<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe Técnico - {{ $report->curriculumReview->career->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; line-height: 1.5; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 5px; }
        h2 { font-size: 14px; margin-top: 20px; }
        h3 { font-size: 13px; margin-top: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .meta { margin-bottom: 20px; }
        .meta table { width: 100%; border-collapse: collapse; }
        .meta td { padding: 3px 5px; font-size: 11px; }
        .meta .label { font-weight: bold; width: 150px; }
        table.criteria { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table.criteria th, table.criteria td { border: 1px solid #ccc; padding: 5px; text-align: left; font-size: 10px; }
        table.criteria th { background-color: #f0f0f0; }
        .content { margin: 20px 0; padding: 15px; border: 1px solid #ddd; white-space: pre-wrap; }
        .footer { margin-top: 30px; border-top: 1px solid #333; padding-top: 10px; font-size: 10px; text-align: center; color: #666; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .badge-green { background-color: #d4edda; color: #155724; }
        .badge-yellow { background-color: #fff3cd; color: #856404; }
        .badge-red { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INFORME TÉCNICO</h1>
        <p>{{ $report->curriculumReview->checklistTemplate->name }} - {{ $report->curriculumReview->checklistTemplate->code }} ({{ $report->curriculumReview->checklistTemplate->version }})</p>
    </div>

    <div class="meta">
        <table>
            <tr><td class="label">Carrera:</td><td>{{ $report->curriculumReview->career->name }}</td></tr>
            <tr><td class="label">Periodo Académico:</td><td>{{ $report->curriculumReview->academicPeriod->name }}</td></tr>
            <tr><td class="label">Acción Curricular:</td><td>{{ $report->curriculumReview->actionType->name }}</td></tr>
            <tr><td class="label">Preparado por:</td><td>{{ $report->preparer->name }}</td></tr>
            <tr><td class="label">Fecha de elaboración:</td><td>{{ $report->created_at->format('d/m/Y') }}</td></tr>
            <tr><td class="label">Estado:</td>
                <td>
                    @if($report->status === 'finalized')
                        <span class="badge badge-green">Finalizado</span>
                    @else
                        <span class="badge badge-yellow">Borrador</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <h2>Resultados de la Lista de Cotejo</h2>
    <table class="criteria">
        <thead>
            <tr>
                <th style="width: 40px;">N°</th>
                <th>Criterio</th>
                <th style="width: 60px;">Puntaje</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->curriculumReview->evaluations as $eval)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $eval->criterion->description }}</td>
                    <td style="text-align: center;">{{ $eval->score }}/5</td>
                    <td>{{ $eval->observations ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Contenido del Informe</h2>
    <div class="content">
        {{ $report->content }}
    </div>

    @if($report->approval)
        <h2>Dictamen del Director de Escuela</h2>
        <p>
            <strong>Decisión:</strong>
            @if($report->approval->decision === 'approved')
                <span class="badge badge-green">APROBADO</span>
            @else
                <span class="badge badge-red">OBSERVADO</span>
            @endif
        </p>
        @if($report->approval->comments)
            <p><strong>Comentarios:</strong> {{ $report->approval->comments }}</p>
        @endif
        <p><strong>Fecha de aprobación:</strong> {{ $report->approval->approved_at->format('d/m/Y H:i') }}</p>
        <p><strong>Aprobado por:</strong> {{ $report->approval->approver->name }}</p>
    @endif

    <div class="footer">
        <p>Documento generado por el Sistema de Gestión Curricular - {{ date('Y') }}</p>
        <p>{{ $report->curriculumReview->checklistTemplate->code }} - Revisión: {{ $report->curriculumReview->checklistTemplate->version }}</p>
    </div>
</body>
</html>
