<div class="mt-6 space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-5">
            <h2 class="text-xl font-semibold text-gray-900">Buscar disponibilidad</h2>
            <p class="text-sm text-gray-500 mt-1">Encuentra el horario perfecto para tu cita.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2">Fecha</label>
                <input type="date" wire:model="searchDate" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2">Hora</label>
                <div class="flex gap-2">
                    <select wire:model.live="searchTimeHour" wire:change="updateSearchTime" class="flex-1 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="12">12</option>
                        @for($i = 1; $i <= 11; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                    <select wire:model.live="searchTimePeriod" wire:change="updateSearchTime" class="w-20 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-2">Especialidad (opcional)</label>
                <select wire:model="searchSpecialty" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    @foreach($doctors->pluck('specialization')->unique()->sort() as $specialty)
                        <option value="{{ $specialty }}">{{ $specialty }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" wire:click="searchAvailability" class="h-11 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition px-5">
                Buscar disponibilidad
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            @forelse($filteredDoctors as $doctor)
                <button type="button" wire:click="selectDoctor({{ $doctor->id }})" class="w-full text-left bg-white rounded-2xl border {{ (int) $doctor_id === (int) $doctor->id ? 'border-indigo-500 ring-2 ring-indigo-100' : 'border-gray-100' }} shadow-sm p-5 hover:border-indigo-300 transition">
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold">
                            {{ strtoupper(substr($doctor->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $doctor->user->name }}</h3>
                                    <p class="text-sm text-indigo-600">{{ $doctor->specialization }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-3 py-1 text-xs font-medium">Disponible</span>
                            </div>
                            <div class="mt-4 border-t border-gray-100 pt-4">
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Horarios disponibles</p>
                                <div class="inline-flex rounded-lg bg-indigo-100 text-indigo-700 px-4 py-2 text-sm font-medium">
                                    {{ $this->getFormattedTime($searchTime ?: '08:00') }} - {{ $this->getFormattedTime($this->getPreviewEndTime($searchTime ?: '08:00')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-500">
                    No hay doctores para los filtros seleccionados.
                </div>
            @endforelse
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de la cita</h3>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Doctor:</span>
                        <span class="font-medium text-gray-900 text-right">{{ $selectedDoctor->user->name ?? 'Selecciona un doctor' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-medium text-gray-900">{{ $date ?: $searchDate }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Horario:</span>
                        <span class="font-medium text-gray-900">{{ $start_time ? $this->getFormattedTime($start_time) : $this->getFormattedTime($searchTime) }} - {{ $end_time ? $this->getFormattedTime($end_time) : $this->getFormattedTime($this->getPreviewEndTime($start_time ?: $searchTime)) }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Duración:</span>
                        <span class="font-medium text-gray-900">{{ $this->getDurationMinutes($start_time, $end_time) }} minutos</span>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-2">Paciente</label>
                        <select wire:model="patient_id" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                            @endforeach
                        </select>
                        @error('patient_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-2">Motivo de la cita</label>
                        <textarea wire:model="reason" rows="5" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escribe el motivo de la cita"></textarea>
                        @error('reason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="button" wire:click="confirmAppointment" class="w-full rounded-xl bg-indigo-600 text-white font-medium py-3 hover:bg-indigo-700 transition">
                        Confirmar cita
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
