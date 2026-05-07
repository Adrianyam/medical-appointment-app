<x-admin-layout tittle="Nueva Cita Médica" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Citas Médicas',
      'href' => route('admin.appointments.index'),
    ],
    [
      'name' => 'Nueva Cita',
    ],
]">

    <x-slot name="action">
        <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
            Volver
        </a>
    </x-slot>

    @livewire('admin.appointment-create-manager')

</x-admin-layout>
