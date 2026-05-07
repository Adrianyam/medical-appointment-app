<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class AppointmentCreateManager extends Component
{
    public Collection $patients;
    public Collection $doctors;
    public Collection $filteredDoctors;

    public $searchDate;
    public $searchTime = '08:00';
    public $searchTimeHour = '08';
    public $searchTimePeriod = 'AM';
    public $searchSpecialty = '';

    public $patient_id = '';
    public $doctor_id = '';
    public $date = '';
    public $start_time = '';
    public $end_time = '';
    public $reason = '';

    public $selectedDoctor = null;

    protected $rules = [
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:doctors,id',
        'date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'reason' => 'nullable|string',
    ];

    public function mount(): void
    {
        $this->searchDate = now()->format('Y-m-d');
        $this->date = now()->format('Y-m-d');
        $this->start_time = $this->searchTime;
        $this->end_time = $this->calculateEndTime($this->searchTime);

        // Solo cargar pacientes con rol 'Paciente'
        $this->patients = Patient::with('user')
            ->whereHas('user.roles', function ($query) {
                $query->where('name', 'Paciente');
            })
            ->orderBy('id')
            ->get();
        
        $this->doctors = Doctor::with('user')->orderBy('id')->get();
        $this->filteredDoctors = $this->doctors;
    }

    public function searchAvailability(): void
    {
        $this->updateSearchTime();
        $this->filteredDoctors = $this->doctors
            ->when($this->searchSpecialty, fn($col) => $col->filter(fn($d) => $d->specialization === $this->searchSpecialty))
            ->values();
    }

    public function updateSearchTime(): void
    {
        $hour = (int)$this->searchTimeHour;
        
        if ($this->searchTimePeriod === 'PM' && $hour !== 12) {
            $hour += 12;
        } elseif ($this->searchTimePeriod === 'AM' && $hour === 12) {
            $hour = 0;
        }
        
        $this->searchTime = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        $this->start_time = $this->searchTime;
        $this->end_time = $this->calculateEndTime($this->searchTime);
    }

    public function selectDoctor(int $doctorId): void
    {
        $this->doctor_id = $doctorId;
        $this->selectedDoctor = $this->doctors->firstWhere('id', $doctorId);
        
        // Establecer los tiempos si no están seteados
        if (!$this->start_time) {
            $this->start_time = $this->searchTime;
            $this->end_time = $this->calculateEndTime($this->searchTime);
        }
    }

    public function updatedSearchTime($value): void
    {
        $this->start_time = $value;
        $this->end_time = $this->calculateEndTime($value);
    }

    public function updatedStartTime($value): void
    {
        $this->end_time = $this->calculateEndTime($value);
    }

    public function updatedDate($value): void
    {
        $this->searchDate = $value;
    }

    public function updatedSearchDate($value): void
    {
        $this->date = $value;
    }

    public function updatedPatientId($value): void
    {
        $this->patient_id = $value;
    }

    public function confirmAppointment()
    {
        $this->date = $this->date ?: $this->searchDate;
        $this->start_time = $this->start_time ?: $this->searchTime;
        $this->end_time = $this->end_time ?: $this->calculateEndTime($this->start_time);

        $validated = $this->validate();

        Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration' => $this->getDurationMinutes($validated['start_time'], $validated['end_time']),
            'reason' => $validated['reason'] ?? null,
            'status' => 1,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita creada',
            'text' => 'La cita médica se registró correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    private function calculateEndTime(string $startTime): string
    {
        return Carbon::createFromFormat('H:i', $startTime)
            ->addMinutes(15)
            ->format('H:i');
    }

    public function getDurationMinutes(?string $startTime, ?string $endTime): int
    {
        if (!$startTime || !$endTime) {
            return 15;
        }

        return Carbon::createFromFormat('H:i', $startTime)
            ->diffInMinutes(Carbon::createFromFormat('H:i', $endTime));
    }

    public function getPreviewEndTime(?string $startTime): string
    {
        return $startTime ? $this->calculateEndTime($startTime) : '08:15';
    }

    public function getFormattedTime(string $time): string
    {
        $carbon = Carbon::createFromFormat('H:i', $time);
        return $carbon->format('h:i A');
    }

    public function render()
    {
        return view('livewire.admin.appointment-create-manager');
    }
}
