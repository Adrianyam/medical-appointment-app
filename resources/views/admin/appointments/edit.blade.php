<x-admin-layout tittle="Editar Cita Médica" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Citas Médicas',
      'href' => route('admin.appointments.index'),
    ],
    [
      'name' => 'Editar Cita',
    ],
]">

<div class="mt-6 max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Editar Cita Médica</h2>

        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Paciente -->
            <div>
                <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Paciente <span class="text-red-500">*</span>
                </label>
                <select id="patient_id" name="patient_id" required 
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        @error('patient_id') class="block w-full px-4 py-2 border border-red-500 rounded-md" @enderror>
                    <option value="">-- Seleccionar Paciente --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" 
                                @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                            {{ $patient->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Doctor -->
            <div>
                <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Doctor <span class="text-red-500">*</span>
                </label>
                <select id="doctor_id" name="doctor_id" required 
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        @error('doctor_id') class="block w-full px-4 py-2 border border-red-500 rounded-md" @enderror>
                    <option value="">-- Seleccionar Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" 
                                @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                            {{ $doctor->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" id="date" name="date" required 
                       value="{{ old('date', $appointment->date->format('Y-m-d')) }}"
                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       @error('date') class="block w-full px-4 py-2 border border-red-500 rounded-md" @enderror>
                @error('date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hora Inicio -->
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                    Hora de Inicio <span class="text-red-500">*</span>
                </label>
                <input type="time" id="start_time" name="start_time" required 
                       value="{{ old('start_time', $appointment->start_time) }}"
                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       @error('start_time') class="block w-full px-4 py-2 border border-red-500 rounded-md" @enderror>
                @error('start_time')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hora Fin -->
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                    Hora de Fin <span class="text-red-500">*</span>
                </label>
                <input type="time" id="end_time" name="end_time" required 
                       value="{{ old('end_time', $appointment->end_time) }}"
                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       @error('end_time') class="block w-full px-4 py-2 border border-red-500 rounded-md" @enderror>
                @error('end_time')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Motivo -->
            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de la Consulta
                </label>
                <textarea id="reason" name="reason" rows="4" 
                          class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          @error('reason') class="block w-full px-4 py-2 border border-red-500 rounded-md" @enderror>{{ old('reason', $appointment->reason) }}</textarea>
                @error('reason')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Actualizar Cita
                </button>
                <a href="{{ route('admin.appointments.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>
