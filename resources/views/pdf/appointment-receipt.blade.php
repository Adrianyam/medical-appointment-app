<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 16px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #6b7280;
        }

        .section {
            margin-bottom: 18px;
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 10px;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px 0;
            vertical-align: top;
        }

        .label {
            width: 32%;
            color: #6b7280;
        }

        .value {
            font-weight: 600;
        }

        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Comprobante de Cita Médica</p>
        <p class="subtitle">Hospital - Resumen oficial de la cita agendada</p>
    </div>

    <div class="section">
        <p class="section-title">Datos de la cita</p>
        <table>
            <tr>
                <td class="label">ID de cita</td>
                <td class="value">#{{ $appointment->id }}</td>
            </tr>
            <tr>
                <td class="label">Fecha</td>
                <td class="value">{{ optional($appointment->date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Hora de inicio</td>
                <td class="value">{{ $appointment->start_time }}</td>
            </tr>
            <tr>
                <td class="label">Hora de fin</td>
                <td class="value">{{ $appointment->end_time }}</td>
            </tr>
            <tr>
                <td class="label">Duración</td>
                <td class="value">{{ $appointment->duration }} minutos</td>
            </tr>
            <tr>
                <td class="label">Motivo</td>
                <td class="value">{{ $appointment->reason ?: 'Sin especificar' }}</td>
            </tr>
            <tr>
                <td class="label">Estado</td>
                <td class="value">{{ $appointment->status }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">Paciente</p>
        <table>
            <tr>
                <td class="label">Nombre</td>
                <td class="value">{{ $appointment->patient?->user?->name ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="label">Correo</td>
                <td class="value">{{ $appointment->patient?->user?->email ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="label">Teléfono</td>
                <td class="value">{{ $appointment->patient?->user?->number_phone ?? 'N/D' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="section-title">Doctor</p>
        <table>
            <tr>
                <td class="label">Nombre</td>
                <td class="value">{{ $appointment->doctor?->user?->name ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="label">Correo</td>
                <td class="value">{{ $appointment->doctor?->user?->email ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="label">Especialidad</td>
                <td class="value">{{ $appointment->doctor?->specialization ?? 'N/D' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Documento generado automáticamente por el sistema de citas.
    </div>
</body>
</html>