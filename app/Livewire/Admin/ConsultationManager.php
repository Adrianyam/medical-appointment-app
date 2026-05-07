<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Consultation;
use Livewire\Component;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    public $activeTab = 'consultation';
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';
    public $medications = [];
    public $newMedicationName = '';
    public $newMedicationDosage = '';
    public $newMedicationFrequency = '';
    public $showPreviousConsultations = false;
    public $showMedicalHistory = false;
    public $showConsultationDetails = false;
    public $selectedConsultation = null;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function toggleMedicalHistory()
    {
        $this->showMedicalHistory = !$this->showMedicalHistory;
    }

    public function viewConsultationDetails($consultationId)
    {
        $consultation = Consultation::find($consultationId);
        if ($consultation) {
            $this->selectedConsultation = [
                'id' => $consultation->id,
                'date' => $consultation->created_at->format('d/m/Y H:i'),
                'doctor' => $consultation->doctor->user->name,
                'diagnosis' => $consultation->diagnosis ?? 'No registrado',
                'treatment' => $consultation->treatment ?? 'No registrado',
                'notes' => $consultation->notes ?? 'Sin notas',
                'medications' => $consultation->medications ?? [],
            ];
            $this->showConsultationDetails = true;
        }
    }

    public function addMedication()
    {
        if ($this->newMedicationName && $this->newMedicationDosage && $this->newMedicationFrequency) {
            $this->medications[] = [
                'name' => $this->newMedicationName,
                'dosage' => $this->newMedicationDosage,
                'frequency' => $this->newMedicationFrequency,
            ];

            $this->newMedicationName = '';
            $this->newMedicationDosage = '';
            $this->newMedicationFrequency = '';
        }
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    public function clearMedicationForm()
    {
        $this->newMedicationName = '';
        $this->newMedicationDosage = '';
        $this->newMedicationFrequency = '';
    }

    public function saveConsultation()
    {
        // Validar que al menos el diagnóstico esté completo
        if (!$this->diagnosis && !$this->treatment) {
            $this->addError('diagnosis', 'Debe completar al menos el diagnóstico o tratamiento.');
            return;
        }

        // Guardar la consulta
        Consultation::create([
            'appointment_id' => $this->appointment->id,
            'patient_id' => $this->appointment->patient_id,
            'doctor_id' => $this->appointment->doctor_id,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'medications' => $this->medications,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Consulta guardada',
            'text' => 'Los datos de la consulta se han guardado correctamente.',
        ]);

        // Disparar evento para que la tabla se refresque
        $this->dispatch('appointmentCreated');

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        // Obtener consultas anteriores del paciente
        $previousConsultations = Consultation::where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id) // Excluir la consulta actual si existe
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($consultation) => [
                'id' => $consultation->id,
                'date' => $consultation->created_at->format('d/m/Y H:i'),
                'doctor' => $consultation->doctor->user->name,
                'diagnosis' => $consultation->diagnosis ?? 'No registrado',
                'treatment' => $consultation->treatment ?? 'No registrado',
            ])
            ->toArray();
        
        return view('livewire.admin.consultation-manager', [
            'previousConsultations' => $previousConsultations,
        ]);
    }
}

