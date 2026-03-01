<x-admin-layout tittle="Roles" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Roles',
    ],
]"> 

  <x-slot name="action">
    <a href="{{ route('admin.roles.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
        <i class="fa-solid fa-plus mr-2"></i> Nuevo Rol
    </a>
</x-slot>

    @livewire('admin.datatables.role-table')

</x-admin-layout>