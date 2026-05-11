<x-admin-layout tittle="{{ $selectedUserId ? 'Completar Doctor' : 'Nuevo Doctor' }}" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Doctores',
      'href' => route('admin.doctors.index'),
    ],
    [
      'name' => 'Nuevo',
    ],
]">

<div class="mt-6 max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $selectedUserId ? 'Completar información del Doctor' : 'Registrar Doctor' }}</h2>

        <form action="{{ route('admin.doctors.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuario <span class="text-red-500">*</span></label>
                <select id="user_id" name="user_id" required class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Seleccionar Usuario --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $selectedUserId ?? null) == $user->id)>{{ $user->name }} - {{ $user->email }}</option>
                    @endforeach
                </select>
                @error('user_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Especialidad <span class="text-red-500">*</span></label>
                <select id="specialization" name="specialization" required class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Seleccionar Especialidad --</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty }}" @selected(old('specialization') === $specialty)>{{ $specialty }}</option>
                    @endforeach
                </select>
                @error('specialization')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">Número de Licencia <span class="text-red-500">*</span></label>
                <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                @error('license_number')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="information" class="block text-sm font-medium text-gray-700 mb-2">Información adicional</label>
                <textarea id="information" name="information" rows="5" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('information') }}</textarea>
                @error('information')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-4 pt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Guardar Doctor</button>
                <a href="{{ route('admin.doctors.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>