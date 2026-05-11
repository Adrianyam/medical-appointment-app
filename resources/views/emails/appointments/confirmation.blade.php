<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
            <div style="padding:24px 28px;background:#111827;color:#ffffff;">
                <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;opacity:.75;">Healthify</div>
                <div style="font-size:24px;font-weight:700;margin-top:8px;">{{ $title }}</div>
                <div style="font-size:14px;margin-top:6px;opacity:.9;">{{ $intro }}</div>
            </div>

            <div style="padding:28px;">
                <p style="margin:0 0 16px 0;font-size:15px;">
                    Hola <strong>{{ $recipientLabel === 'Doctor' ? ($appointment->doctor?->user?->name ?? 'Doctor') : ($appointment->patient?->user?->name ?? 'Paciente') }}</strong>,
                </p>

                <p style="margin:0 0 20px 0;font-size:14px;line-height:1.7;color:#4b5563;">
                    Tu cita ha sido registrada correctamente. A continuación se muestran los datos principales y el comprobante adjunto en PDF.
                </p>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;width:38%;">ID de cita</td>
                        <td style="padding:12px 16px;">#{{ $appointment->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Fecha</td>
                        <td style="padding:12px 16px;">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Horario</td>
                        <td style="padding:12px 16px;">{{ substr($appointment->start_time, 0, 5) }} - {{ substr($appointment->end_time, 0, 5) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Duración</td>
                        <td style="padding:12px 16px;">{{ $appointment->duration }} minutos</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Paciente</td>
                        <td style="padding:12px 16px;">{{ $appointment->patient?->user?->name ?? 'N/D' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Doctor</td>
                        <td style="padding:12px 16px;">{{ $appointment->doctor?->user?->name ?? 'N/D' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Especialidad</td>
                        <td style="padding:12px 16px;">{{ $appointment->doctor?->specialization ?? 'N/D' }}</td>
                    </tr>
                    @if($appointment->reason)
                        <tr>
                            <td style="padding:12px 16px;background:#f9fafb;font-weight:700;">Motivo</td>
                            <td style="padding:12px 16px;">{{ $appointment->reason }}</td>
                        </tr>
                    @endif
                </table>

                <div style="margin-top:20px;padding:14px 16px;background:#fef3c7;border-left:4px solid #f59e0b;border-radius:8px;font-size:13px;color:#92400e;">
                    El comprobante PDF va adjunto a este correo.
                </div>

                <p style="margin:20px 0 0 0;font-size:13px;line-height:1.7;color:#6b7280;">
                    Si necesitas reprogramar la cita, comunícate con el equipo de atención al paciente.
                </p>
            </div>

            <div style="padding:18px 28px;border-top:1px solid #e5e7eb;background:#fafafa;font-size:12px;color:#6b7280;text-align:center;">
                Sistema de Citas Médicas - Healthify. Mensaje automático, por favor no respondas.
            </div>
        </div>
    </div>
</body>
</html>