@component('mail::message')
# Lista General de Pacientes

Hola Administrador,

Este es el reporte con la lista de todos los pacientes registrados en el sistema hasta el momento (**{{ now()->format('d/m/Y H:i') }}**).

| Nombre | Correo | Teléfono | Identificación |
| :--- | :--- | :--- | :--- |
@foreach($patients as $patient)
| {{ $patient->user->name }} | {{ $patient->user->email }} | {{ $patient->user->number_phone }} | {{ $patient->user->id_number }} |
@endforeach

Gracias,<br>
{{ config('app.name') }}
@endcomponent
