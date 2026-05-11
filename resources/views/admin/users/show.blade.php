<x-admin-layout tittle="Información del Usuario" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Usuarios',
      'href' => route('admin.users.index'),
    ],
    [
      'name' => 'Información',
    ],
]">

<div class="mt-6 max-w-4xl mx-auto space-y-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-gray-600">DNI: {{ $user->id_number }}</p>
                <p class="text-gray-600">Teléfono: {{ $user->number_phone }}</p>
                <p class="text-gray-600">Dirección: {{ $user->address }}</p>
                <p class="text-gray-600">Roles: {{ $user->roles->pluck('name')->join(', ') }}</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Editar
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-sm text-gray-500 uppercase">Estado médico</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">
                @if($user->doctor)
                    Doctor registrado
                @else
                    Pendiente de completar doctor
                @endif
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-sm text-gray-500 uppercase">Tipo de perfil</p>
            <p class="mt-2 text-lg font-semibold text-gray-900">
                {{ $user->doctor ? 'Doctor' : 'Usuario' }}
            </p>
        </div>
    </div>
</div>

</x-admin-layout>