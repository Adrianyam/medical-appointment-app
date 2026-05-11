<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #1f2937; }
        .wrapper { max-width: 760px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
        .header { background: linear-gradient(135deg, #166534, #22c55e); color: #fff; padding: 28px; }
        .header h1 { margin: 0; font-size: 26px; }
        .header p { margin: 8px 0 0; opacity: .9; }
        .content { padding: 28px; }
        .notice { background: #ecfdf5; border: 1px solid #bbf7d0; color: #166534; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { background: #f0fdf4; color: #166534; font-size: 13px; text-transform: uppercase; letter-spacing: .04em; }
        tr:nth-child(even) td { background: #f8fafc; }
        .footer { padding: 18px 28px 28px; font-size: 12px; color: #64748b; }
        @media (max-width: 640px) {
            .wrapper { padding: 12px; }
            .content, .header { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>Agenda del día</h1>
                <p>{{ $date }}</p>
            </div>

            <div class="content">
                <p>Hola Dr. {{ $doctor?->user?->name }}, estas son tus citas programadas para hoy.</p>

                <div class="notice">
                    Tienes {{ $appointments->count() }} cita(s) agendada(s) para esta jornada.
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Teléfono</th>
                            <th>Duración</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td>{{ substr((string) $appointment->start_time, 0, 5) }} - {{ substr((string) $appointment->end_time, 0, 5) }}</td>
                                <td>{{ $appointment->patient?->user?->name }}</td>
                                <td>{{ $appointment->patient?->user?->number_phone ?? 'N/A' }}</td>
                                <td>{{ $appointment->duration }} min</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="footer">
                Mensaje automático generado por Healthify para doctores.
            </div>
        </div>
    </div>
</body>
</html>