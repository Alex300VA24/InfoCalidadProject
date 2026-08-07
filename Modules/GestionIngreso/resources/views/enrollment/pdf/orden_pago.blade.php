<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Orden de Pago</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111827; }
        .header { text-align: center; border-bottom: 3px solid #1a237e; padding-bottom: 8px; }
        .header h1 { margin: 0; font-size: 16px; color: #1a237e; }
        .header p { margin: 2px 0; font-size: 10px; color: #4b5563; }
        .title { text-align: center; font-size: 13px; font-weight: bold; margin: 20px 0; text-transform: uppercase; }
        .code { text-align: right; font-size: 9px; color: #6b7280; margin-bottom: 4px; }
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { border: 1px solid #cbd5e1; padding: 8px 10px; }
        table.info td.label { width: 180px; background: #f1f5f9; font-weight: bold; }
        .amount { font-size: 20px; font-weight: bold; color: #1a237e; }
        .footer { margin-top: 40px; text-align: center; }
        .signature { border-top: 1px solid #111827; width: 260px; margin: 0 auto; padding-top: 4px; font-weight: bold; font-size: 10px; }
        .status { text-transform: uppercase; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Universidad Nacional de Trujillo</h1>
        <p>Unidad de Matrícula</p>
    </div>

    <p class="code">Formato F2 · OP-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
    <div class="title">Orden de Pago</div>

    <table class="info">
        <tr>
            <td class="label">Estudiante</td>
            <td>{{ $payment->student?->fullName() }}</td>
            <td class="label">Código</td>
            <td>{{ $payment->student?->codigo }}</td>
        </tr>
        <tr>
            <td class="label">Concepto</td>
            <td>{{ $payment->concept }}</td>
            <td class="label">Periodo</td>
            <td>{{ $payment->enrollment?->academicPeriod?->name }}</td>
        </tr>
        <tr>
            <td class="label">Estado</td>
            <td class="status">{{ $payment->status }}</td>
            <td class="label">Monto a pagar</td>
            <td class="amount">S/ {{ number_format($payment->amount, 2) }}</td>
        </tr>
    </table>

    <p style="margin-top: 24px; font-size: 10px; color: #4b5563;">
        Realice el pago en los bancos autorizados y presente la constancia de pago en la Unidad de Matrícula.
        Esta orden tiene validez hasta {{ now()->addDays(15)->format('d/m/Y') }}.
    </p>

    <div class="footer">
        <div class="signature">Unidad de Matrícula</div>
    </div>
</body>
</html>
