<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentConfirmationEmailService;
use App\Services\AppointmentReceiptPdfService;
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
    public $searchTimeMinute = '00';
    public $searchTimePeriod = 'AM';
    public $searchSpecialty = '';
    public $searchEndHour = '08';
    public $searchEndMinute = '15';
    public $searchEndPeriod = 'AM';

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

        $desiredStart = Carbon::createFromFormat('H:i', $this->start_time);
        $desiredEnd = Carbon::createFromFormat('H:i', $this->end_time);

        $weekday = strtolower(Carbon::createFromFormat('Y-m-d', $this->searchDate)->format('l'));

        $this->filteredDoctors = $this->doctors
            ->when($this->searchSpecialty, fn($col) => $col->filter(fn($d) => $d->specialization === $this->searchSpecialty))
            ->filter(function ($doctor) use ($weekday, $desiredStart, $desiredEnd) {
                $schedule = $doctor->schedule[$weekday] ?? null;

                // New format: ['active' => bool, 'start' => 'HH:MM', 'end' => 'HH:MM']
                if (is_array($schedule) && array_key_exists('active', $schedule)) {
                    if (!$schedule['active']) {
                        return false;
                    }

                    if (empty($schedule['start']) || empty($schedule['end'])) {
                        return false;
                    }

                    try {
                        $s = Carbon::createFromFormat('H:i', $schedule['start']);
                        $e = Carbon::createFromFormat('H:i', $schedule['end']);
                    } catch (\Throwable $th) {
                        return false;
                    }

                    return $desiredStart->greaterThanOrEqualTo($s) && $desiredEnd->lessThanOrEqualTo($e);
                }

                // Backwards compatibility: old format is array of slots like ['08:00-08:15', ...]
                if (is_array($schedule) && !empty($schedule)) {
                    $first = $schedule[0];
                    $last = end($schedule);
                    if (is_string($first) && str_contains($first, '-') && is_string($last) && str_contains($last, '-')) {
                        [$fs] = explode('-', $first);
                        [, $le] = explode('-', $last);

                        try {
                            $s = Carbon::createFromFormat('H:i', $fs);
                            $e = Carbon::createFromFormat('H:i', $le);
                        } catch (\Throwable $th) {
                            return false;
                        }

                        return $desiredStart->greaterThanOrEqualTo($s) && $desiredEnd->lessThanOrEqualTo($e);
                    }
                }

                return false;
            })
            ->values();
    }

    public function updateSearchTime(): void
    {
        $hour = (int)$this->searchTimeHour;
        $minute = (int) ($this->searchTimeMinute ?? '00');

        if ($this->searchTimePeriod === 'PM' && $hour !== 12) {
            $hour += 12;
        } elseif ($this->searchTimePeriod === 'AM' && $hour === 12) {
            $hour = 0;
        }

        $this->start_time = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string)$minute, 2, '0', STR_PAD_LEFT);

        // calcular end_time a partir de end selectors si provistos
        $endHour = (int)$this->searchEndHour;
        $endMinute = (int)($this->searchEndMinute ?? '00');
        if ($this->searchEndPeriod === 'PM' && $endHour !== 12) {
            $endHour += 12;
        } elseif ($this->searchEndPeriod === 'AM' && $endHour === 12) {
            $endHour = 0;
        }

        $this->end_time = str_pad($endHour, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string)$endMinute, 2, '0', STR_PAD_LEFT);
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

        // validar disponibilidad del doctor seleccionado
        $doctor = Doctor::find($validated['doctor_id']);
        if (!$doctor) {
            $this->addError('doctor_id', 'Doctor no encontrado.');
            return;
        }

        $weekday = strtolower(Carbon::createFromFormat('Y-m-d', $validated['date'])->format('l'));
        $schedule = $doctor->schedule[$weekday] ?? null;

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);

        $allowed = false;
        if (is_array($schedule) && array_key_exists('active', $schedule)) {
            if ($schedule['active'] && !empty($schedule['start']) && !empty($schedule['end'])) {
                try {
                    $s = Carbon::createFromFormat('H:i', $schedule['start']);
                    $e = Carbon::createFromFormat('H:i', $schedule['end']);
                    $allowed = $start->greaterThanOrEqualTo($s) && $end->lessThanOrEqualTo($e);
                } catch (\Throwable $th) {
                    $allowed = false;
                }
            }
        } elseif (is_array($schedule) && !empty($schedule)) {
            $first = $schedule[0];
            $last = end($schedule);
            if (is_string($first) && str_contains($first, '-') && is_string($last) && str_contains($last, '-')) {
                [$fs] = explode('-', $first);
                [, $le] = explode('-', $last);
                try {
                    $s = Carbon::createFromFormat('H:i', $fs);
                    $e = Carbon::createFromFormat('H:i', $le);
                    $allowed = $start->greaterThanOrEqualTo($s) && $end->lessThanOrEqualTo($e);
                } catch (\Throwable $th) {
                    $allowed = false;
                }
            }
        }

        if (!$allowed) {
            $this->addError('doctor_id', 'El doctor no está disponible en la franja horaria seleccionada.');
            return;
        }

        // verificar solapamiento con otras citas
        $conflict = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('date', $validated['date'])
            ->where(function ($q) use ($validated) {
                $q->where('start_time', '<', $validated['end_time'])
                  ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($conflict) {
            $this->addError('doctor_id', 'El doctor tiene otra cita en ese horario.');
            return;
        }

        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration' => $this->getDurationMinutes($validated['start_time'], $validated['end_time']),
            'reason' => $validated['reason'] ?? null,
            'status' => 1,
        ]);

        $pdfPath = app(AppointmentReceiptPdfService::class)->generate($appointment);
        app(AppointmentConfirmationEmailService::class)->send($appointment, $pdfPath);

        session()->flash('appointment_receipt_pdf', $pdfPath);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita creada',
            'text' => 'La cita médica se registró correctamente, se generó el comprobante en PDF y se enviaron los correos.',
        ]);

        $this->redirectRoute('admin.appointments.index');
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
