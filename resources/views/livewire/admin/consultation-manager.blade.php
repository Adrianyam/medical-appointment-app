<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Consulta Médica</h1>
                    <p class="text-gray-600 mt-2">Paciente: <strong>{{ $appointment->patient->user->name }}</strong></p>
                    <p class="text-gray-600">Doctor: <strong>{{ $appointment->doctor->user->name }}</strong></p>
                    <p class="text-gray-600">Fecha: <strong>{{ $appointment->date->format('d/m/Y') }}</strong> 
                       de {{ $appointment->start_time }} a {{ $appointment->end_time }}</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="toggleMedicalHistory" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-file-medical mr-2"></i>Ver Historial Médico
                    </button>
                    <button wire:click="$toggle('showPreviousConsultations')" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                        <i class="fas fa-history mr-2"></i>Consultas Anteriores
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 flex">
                <button wire:click="$set('activeTab', 'consultation')" 
                        class="flex-1 py-4 px-6 font-medium transition-all {{ $activeTab === 'consultation' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fas fa-file-medical-alt mr-2"></i>Consulta
                </button>
                <button wire:click="$set('activeTab', 'prescription')" 
                        class="flex-1 py-4 px-6 font-medium transition-all {{ $activeTab === 'prescription' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                    <i class="fas fa-prescription-bottle-medical mr-2"></i>Receta
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Consulta Tab -->
                @if($activeTab === 'consultation')
                    <div class="space-y-6">
                        <!-- Diagnóstico -->
                        <div>
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">
                                Diagnóstico <span class="text-red-500">*</span>
                            </label>
                            <textarea id="diagnosis" wire:model="diagnosis" rows="4" 
                                      placeholder="Ingrese el diagnóstico del paciente"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <!-- Tratamiento -->
                        <div>
                            <label for="treatment" class="block text-sm font-medium text-gray-700 mb-2">
                                Tratamiento <span class="text-red-500">*</span>
                            </label>
                            <textarea id="treatment" wire:model="treatment" rows="4" 
                                      placeholder="Ingrese el tratamiento recomendado"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <!-- Notas -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notas Clínicas
                            </label>
                            <textarea id="notes" wire:model="notes" rows="3" 
                                      placeholder="Notas adicionales sobre la consulta"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                @endif

                <!-- Receta Tab -->
                @if($activeTab === 'prescription')
                    <div class="space-y-6">
                        <!-- Agregar Medicamentos -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Agregar Medicamento</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="medicationName" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Medicamento
                                    </label>
                                    <input type="text" id="medicationName" wire:model="newMedicationName" 
                                           placeholder="Ej: Amoxicilina"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="medicationDosage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dosis
                                    </label>
                                    <input type="text" id="medicationDosage" wire:model="newMedicationDosage" 
                                           placeholder="Ej: 500mg"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="medicationFrequency" class="block text-sm font-medium text-gray-700 mb-2">
                                        Frecuencia
                                    </label>
                                    <input type="text" id="medicationFrequency" wire:model="newMedicationFrequency" 
                                           placeholder="Ej: Cada 8 horas"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="addMedication" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    <i class="fas fa-plus mr-2"></i>Agregar Medicamento
                                </button>
                                <button type="button" wire:click="clearMedicationForm" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    <i class="fas fa-trash mr-2"></i>Limpiar
                                </button>
                            </div>
                        </div>

                        <!-- Lista de Medicamentos -->
                        @if(count($medications) > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Medicamentos Prescritos</h3>
                                <div class="space-y-3">
                                    @foreach($medications as $index => $medication)
                                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $medication['name'] }}</p>
                                                <p class="text-sm text-gray-600">{{ $medication['dosage'] }} - {{ $medication['frequency'] }}</p>
                                            </div>
                                            <button wire:click="removeMedication({{ $index }})" 
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <p class="text-gray-500">No hay medicamentos agregados aún</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex gap-4">
            <button wire:click="saveConsultation" 
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium">
                <i class="fas fa-save mr-2"></i>Guardar Consulta
            </button>
            <a href="{{ route('admin.appointments.index') }}" 
               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition font-medium">
                <i class="fas fa-times mr-2"></i>Cancelar
            </a>
        </div>
    </div>

    <!-- Modal de Consultas Anteriores -->
    @if($showPreviousConsultations)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-96 overflow-auto">
                <div class="sticky top-0 bg-gray-100 px-6 py-4 flex justify-between items-center border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Consultas Anteriores</h3>
                    <button wire:click="$toggle('showPreviousConsultations')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    @if(count($previousConsultations) > 0)
                        <div class="space-y-4">
                            @foreach($previousConsultations as $consultation)
                                <div class="p-4 border-l-4 border-blue-500 bg-blue-50 rounded">
                                    <p class="font-medium text-gray-900">{{ $consultation['date'] }}</p>
                                    <p class="text-sm text-gray-600">Doctor: {{ $consultation['doctor'] }}</p>
                                    <p class="text-sm text-gray-700 mt-2"><strong>Diagnóstico:</strong> {{ $consultation['diagnosis'] }}</p>
                                    <p class="text-sm text-gray-700"><strong>Tratamiento:</strong> {{ $consultation['treatment'] }}</p>
                                    <button wire:click="viewConsultationDetails({{ $consultation['id'] }})" class="mt-3 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                        <i class="fas fa-eye mr-1"></i>Ver detalles
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No hay consultas anteriores registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Historial Médico -->
    @if($showMedicalHistory)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-auto overflow-auto">
                <div class="sticky top-0 bg-gray-100 px-6 py-4 flex justify-between items-center border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Historial médico del paciente</h3>
                    <button wire:click="toggleMedicalHistory" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Tipo de sangre</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $appointment->patient->bloodType->type ?? 'No registrado' }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Alergias</p>
                            <p class="text-sm text-gray-900">{{ $appointment->patient->allergies ?? 'No registradas' }}</p>
                        </div>
                        <div class="border-l-4 border-yellow-500 pl-4">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Enfermedades crónicas</p>
                            <p class="text-sm text-gray-900">{{ $appointment->patient->chronic_conditions ?? 'No registradas' }}</p>
                        </div>
                        <div class="border-l-4 border-red-500 pl-4">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Antecedentes quirúrgicos</p>
                            <p class="text-sm text-gray-900">{{ $appointment->patient->surgical_history ?? 'No registrados' }}</p>
                        </div>
                    </div>

                    @if($appointment->patient->family_history)
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Historial Familiar</p>
                            <p class="text-sm text-gray-900">{{ $appointment->patient->family_history }}</p>
                        </div>
                    @endif

                    @if($appointment->patient->observations)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Observaciones</p>
                            <p class="text-sm text-gray-900">{{ $appointment->patient->observations }}</p>
                        </div>
                    @endif
                </div>
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <a href="{{ route('admin.patients.edit', $appointment->patient->id) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        <i class="fas fa-edit mr-1"></i>Editar Historial Médico
                    </a>
                    <button wire:click="toggleMedicalHistory" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition font-medium">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Detalles de Consulta -->
    @if($showConsultationDetails && $selectedConsultation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-auto">
                <div class="sticky top-0 bg-gray-100 px-6 py-4 flex justify-between items-center border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Detalles de Consulta</h3>
                    <button wire:click="$set('showConsultationDetails', false)" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Información general -->
                    <div class="grid grid-cols-2 gap-4 pb-6 border-b border-gray-200">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Fecha</p>
                            <p class="text-sm font-medium text-gray-900">{{ $selectedConsultation['date'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Doctor</p>
                            <p class="text-sm font-medium text-gray-900">{{ $selectedConsultation['doctor'] }}</p>
                        </div>
                    </div>

                    <!-- Diagnóstico -->
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-2">Diagnóstico</p>
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-gray-700">{{ $selectedConsultation['diagnosis'] }}</p>
                        </div>
                    </div>

                    <!-- Tratamiento -->
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-2">Tratamiento</p>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm text-gray-700">{{ $selectedConsultation['treatment'] }}</p>
                        </div>
                    </div>

                    <!-- Notas -->
                    @if($selectedConsultation['notes'] && $selectedConsultation['notes'] !== 'Sin notas')
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-2">Notas Clínicas</p>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-700">{{ $selectedConsultation['notes'] }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Medicamentos -->
                    @if(count($selectedConsultation['medications']) > 0)
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-2">Medicamentos Prescritos</p>
                            <div class="space-y-2">
                                @foreach($selectedConsultation['medications'] as $medication)
                                    <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <p class="text-sm font-medium text-gray-900">{{ $medication['name'] }}</p>
                                        <div class="flex gap-4 mt-1 text-xs text-gray-600">
                                            <span><strong>Dosis:</strong> {{ $medication['dosage'] }}</span>
                                            <span><strong>Frecuencia:</strong> {{ $medication['frequency'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end">
                    <button wire:click="$set('showConsultationDetails', false)" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition font-medium">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

