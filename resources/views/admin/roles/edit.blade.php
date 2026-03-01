<x-admin-layout tittle="Editar Rol" :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Roles', 'href' => route('admin.roles.index')],
    ['name' => 'Editar'],
]">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"> {{-- Sustituye a x-wire-card --}}
        
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block font-medium text-sm text-gray-700">Nombre del rol</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $role->name) }}" 
                       placeholder="Nombre del rol"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                @error('name')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Actualizar
                </button>
            </div>
        </form>

    </div>
</x-admin-layout>