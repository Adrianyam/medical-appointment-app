<x-admin-layout tittle="Citas Médicas" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Citas Médicas',
    ],
]">

    <x-slot name="action">
        <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Nuevo
        </a>
    </x-slot>

  @livewire('admin.datatables.appointment-table')

</x-admin-layout>
