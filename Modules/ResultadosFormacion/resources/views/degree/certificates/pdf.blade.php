<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $certificate->code }} - {{ $certificate->typeLabel() }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111827; }
        .header { text-align: center; border-bottom: 3px solid #1a237e; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #1a237e; }
        .header p { margin: 3px 0; font-size: 11px; color: #4b5563; }
        .code { text-align: right; font-size: 10px; color: #6b7280; margin-top: 12px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin: 35px 0 25px; text-transform: uppercase; letter-spacing: 1px; }
        .body-text { text-align: justify; line-height: 1.8; margin: 0 25px; }
        .signature-block { margin-top: 90px; }
        .sig { width: 40%; display: inline-block; text-align: center; }
        .sig .line { border-top: 1px solid #111827; padding-top: 6px; font-weight: bold; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Universidad Nacional de Trujillo</h1>
        <p>Facultad de Ingeniería · Unidad de Grados y Títulos</p>
        <p>Dirección de Registro Técnico</p>
    </div>

    <p class="code">{{ $certificate->code }}</p>
    <div class="title">{{ $certificate->typeLabel() }}</div>

    <div class="body-text">
        <p>La Universidad Nacional de Trujillo, por medio de la Unidad de Grados y Títulos, deja constancia que el estudiante:</p>
        <p style="font-size:14px; font-weight:bold; text-align:center; margin:20px 0;">
            {{ $certificate->student?->fullName() }}<br>
            <span style="font-size:11px; font-weight:normal; color:#4b5563;">Código: {{ $certificate->student?->codigo }}</span>
        </p>
        <p>tiene la calidad de: <strong>{{ $certificate->typeLabel() }}</strong>, por el siguiente concepto:</p>
        <p style="font-style:italic; text-align:center; margin:15px 0;">{{ $certificate->concept }}</p>
        <p>Se expide el presente certificado a solicitud del interesado, para los fines que estime conveniente, en la ciudad de Trujillo, a los {{ $certificate->issued_at?->format('d') }} días del mes de {{ ucfirst($certificate->issued_at?->translatedFormat('F') ?? '') }} de {{ $certificate->issued_at?->format('Y') }}.</p>
    </div>

    <div class="signature-block">
        <span class="sig"><div class="line">{{ $certificate->issued_by }}</div></span>
        <span class="sig"><div class="line">Unidad de Grados y Títulos</div></span>
    </div>
</body>
</html>
