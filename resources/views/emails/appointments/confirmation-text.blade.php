{{ $title }}

{{ $intro }}

Hola {{ $recipientLabel === 'Doctor' ? ($appointment->doctor?->user?->name ?? 'Doctor') : ($appointment->patient?->user?->name ?? 'Paciente') }},

Tu cita ha sido registrada correctamente.

Datos de la cita:
- ID: #{{ $appointment->id }}
- Fecha: {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
- Horario: {{ substr($appointment->start_time, 0, 5) }} - {{ substr($appointment->end_time, 0, 5) }}
- Duración: {{ $appointment->duration }} minutos
- Paciente: {{ $appointment->patient?->user?->name ?? 'N/D' }}
- Doctor: {{ $appointment->doctor?->user?->name ?? 'N/D' }}
- Especialidad: {{ $appointment->doctor?->specialization ?? 'N/D' }}
@if($appointment->reason)
- Motivo: {{ $appointment->reason }}
@endif

El comprobante PDF va adjunto a este correo.

Sistema de Citas Médicas - Healthify