@component('mail::message')
# Reporte Diario de Citas Médicas

Hola Administrador,

Este es el resumen de las citas programadas para el día de hoy, **{{ \Carbon\Carbon::today()->format('d/m/Y') }}**.

@if($appointments->count() > 0)
@foreach($appointments->groupBy('doctor_id') as $doctorId => $doctorAppointments)
@php $doctor = $doctorAppointments->first()->doctor; @endphp
## Doctor: {{ $doctor->user->name }} ({{ $doctor->specialization }})

| Paciente | Horario | Estado |
| :--- | :--- | :--- |
@foreach($doctorAppointments as $appointment)
| {{ $appointment->patient->user->name }} | {{ substr($appointment->start_time, 0, 5) }} - {{ substr($appointment->end_time, 0, 5) }} | {{ $appointment->status == 1 ? 'Pendiente' : 'Completada' }} |
@endforeach

---
@endforeach
@else
No hay citas programadas para el día de hoy.
@endif

Gracias,<br>
{{ config('app.name') }}
@endcomponent
