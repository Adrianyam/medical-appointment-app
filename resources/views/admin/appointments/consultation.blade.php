<x-admin-layout tittle="Consulta Médica" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Citas Médicas',
      'href' => route('admin.appointments.index'),
    ],
    [
      'name' => 'Consulta Médica',
    ],
]">

    @livewire('admin.consultation-manager', ['appointment' => $appointment])

</x-admin-layout>
