<x-admin-layout tittle="Información del Doctor" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Doctores',
      'href' => route('admin.doctors.index'),
    ],
    [
      'name' => 'Información',
    ],
]">

<div class="mt-6 max-w-5xl mx-auto space-y-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $doctor->user->name }}</h2>
                <p class="text-gray-600">{{ $doctor->user->email }}</p>
                <p class="text-gray-600">DNI: {{ $doctor->user->id_number }}</p>
                <p class="text-gray-600">{{ $doctor->user->number_phone }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Editar
                </a>
                <a href="{{ route('admin.doctors.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-sm text-gray-500 uppercase">Especialidad</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $doctor->specialization }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-sm text-gray-500 uppercase">DNI</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $doctor->user->id_number }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-sm text-gray-500 uppercase">Citas registradas</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $doctor->appointments->count() }}</p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Información del Doctor</h3>
        <p class="text-gray-700 leading-7 whitespace-pre-line">
            {{ $doctor->information ?: 'No hay información adicional registrada para este doctor.' }}
        </p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Últimas Citas</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">Paciente</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Hora</th>
                        <th class="px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctor->appointments->take(5) as $appointment)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3">{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $appointment->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                            <td class="px-4 py-3">
                                @if($appointment->status == 1)
                                    Programada
                                @elseif($appointment->status == 2)
                                    En Consulta
                                @elseif($appointment->status == 3)
                                    Completada
                                @else
                                    Cancelada
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">No hay citas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-admin-layout>