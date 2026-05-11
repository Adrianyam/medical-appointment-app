<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background: #f4f7fb; color: #1f2937; }
        .wrapper { max-width: 760px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
        .header { background: linear-gradient(135deg, #0f172a, #2563eb); color: #fff; padding: 28px; }
        .header h1 { margin: 0; font-size: 26px; }
        .header p { margin: 8px 0 0; opacity: .9; }
        .content { padding: 28px; }
        .summary { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
        .summary div { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; }
        .summary strong { display: block; font-size: 13px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .04em; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { background: #eff6ff; color: #1d4ed8; font-size: 13px; text-transform: uppercase; letter-spacing: .04em; }
        tr:nth-child(even) td { background: #f8fafc; }
        .footer { padding: 18px 28px 28px; font-size: 12px; color: #64748b; }
        @media (max-width: 640px) {
            .wrapper { padding: 12px; }
            .content, .header { padding: 20px; }
            .summary { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>Reporte diario de citas</h1>
                <p>{{ $date }}</p>
            </div>

            <div class="content">
                <div class="summary">
                    <div>
                        <strong>Total de citas</strong>
                        <span>{{ $appointments->count() }}</span>
                    </div>
                    <div>
                        <strong>Doctores con agenda</strong>
                        <span>{{ $appointments->pluck('doctor_id')->unique()->count() }}</span>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Hora</th>
                            <th>Especialidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->id }}</td>
                                <td>{{ $appointment->patient?->user?->name }}</td>
                                <td>{{ $appointment->doctor?->user?->name }}</td>
                                <td>{{ substr((string) $appointment->start_time, 0, 5) }} - {{ substr((string) $appointment->end_time, 0, 5) }}</td>
                                <td>{{ $appointment->doctor?->specialization }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="footer">
                Mensaje automático generado por Healthify para administración.
            </div>
        </div>
    </div>
</body>
</html>