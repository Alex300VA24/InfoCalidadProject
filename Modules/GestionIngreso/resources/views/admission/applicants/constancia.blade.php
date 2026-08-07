<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Constancia de Ingreso</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111827; }
        .header { text-align: center; border-bottom: 3px solid #1a237e; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #1a237e; }
        .header p { margin: 2px 0; font-size: 11px; color: #4b5563; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin: 24px 0; text-transform: uppercase; }
        .body { margin: 0 30px; line-height: 1.7; text-align: justify; }
        .body strong { color: #1a237e; }
        .code { margin: 0 30px; text-align: right; font-size: 10px; color: #6b7280; margin-top: 10px; }
        .footer { margin-top: 60px; text-align: center; }
        .signature { border-top: 1px solid #111827; width: 260px; margin: 0 auto; padding-top: 6px; font-weight: bold; }
        .signature small { display: block; font-weight: normal; color: #4b5563; }
        .meta { margin: 16px 30px 0; font-size: 11px; }
        .meta td { padding: 3px 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Universidad Nacional de Trujillo</h1>
        <p>Dirección de Admisión</p>
        <p>{{ $applicant->admissionProcess?->career?->faculty?->name ?? 'Facultad de Ingeniería Informática' }}</p>
    </div>

    <p class="code">Formato F-DAD-PG-017</p>
    <div class="title">Constancia de Ingreso</div>

    <div class="body">
        <p>Por medio del presente, se deja constancia que el(la) postulante
            <strong>{{ $applicant->fullName() }}</strong>, identificado(a) con DNI
            <strong>{{ $applicant->dni }}</strong>, ha sido declarado
            <strong>INGRESANTE</strong> en el proceso de admisión
            <strong>{{ $applicant->admissionProcess?->name }}</strong>
            (modalidad <strong>{{ $applicant->admissionProcess?->modality }}</strong>),
            correspondiente al periodo académico
            <strong>{{ $applicant->admissionProcess?->academicPeriod?->name }}</strong>,
            para la carrera profesional de
            <strong>{{ $applicant->career?->name }}</strong>, con un puntaje de
            <strong>{{ $applicant->score }}</strong> puntos.</p>
    </div>

    <table class="meta">
        <tr><td><strong>Fecha de emisión:</strong></td><td>{{ now()->format('d/m/Y') }}</td></tr>
        <tr><td><strong>Código de constancia:</strong></td><td>CI-{{ $applicant->admissionProcess_id }}-{{ str_pad($applicant->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
    </table>

    <div class="footer">
        <div class="signature">
            Director de Admisión
            <small>Dirección de Admisión - UNT</small>
        </div>
    </div>
</body>
</html>
