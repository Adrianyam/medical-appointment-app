<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    private function specialties(): array
    {
        return [
            'Medicina General',
            'Dermatología',
            'Cardiología',
            'Pediatría',
            'Ginecología',
        ];
    }

    public function index()
    {
        return view('admin.doctors.index');
    }

    public function create()
    {
        $selectedUserId = request('user_id');

        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'Medico');
            })
            ->whereDoesntHave('doctor')
            ->whereDoesntHave('patient')
            ->orderBy('name')
            ->get();

        return view('admin.doctors.create', [
            'users' => $users,
            'selectedUserId' => $selectedUserId,
            'specialties' => $this->specialties(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:doctors,user_id'],
            'specialization' => ['required', Rule::in($this->specialties())],
            'license_number' => ['required', 'string', 'max:255', 'unique:doctors,license_number'],
            'information' => ['nullable', 'string'],
        ]);

        Doctor::create($data);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor creado correctamente.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user', 'appointments.patient.user');

        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'Medico');
            })
            ->where(function ($query) use ($doctor) {
                $query->whereDoesntHave('doctor')
                    ->whereDoesntHave('patient')
                    ->orWhere('id', $doctor->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.doctors.edit', [
            'doctor' => $doctor,
            'users' => $users,
            'specialties' => $this->specialties(),
        ]);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('doctors', 'user_id')->ignore($doctor->id),
            ],
            'specialization' => ['required', Rule::in($this->specialties())],
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('doctors', 'license_number')->ignore($doctor->id),
            ],
            'information' => ['nullable', 'string'],
        ]);

        $doctor->update($data);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor actualizado correctamente.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor eliminado correctamente.');
    }

    public function editDoctorInfo(User $user)
    {
        // Cargar el doctor si existe
        $user->load('doctor');
        
        $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'Medico');
            })
            ->where(function ($query) use ($user) {
                $query->whereDoesntHave('doctor')
                    ->whereDoesntHave('patient')
                    ->orWhere('id', $user->id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.doctors.edit-user-doctor', [
            'user' => $user,
            'doctor' => $user->doctor,
            'users' => $users,
            'specialties' => $this->specialties(),
        ]);
    }

    public function updateDoctorInfo(Request $request, User $user)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('doctors', 'user_id')->ignore($user->doctor?->id),
            ],
            'specialization' => ['required', Rule::in($this->specialties())],
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('doctors', 'license_number')->ignore($user->doctor?->id),
            ],
            'information' => ['nullable', 'string'],
        ]);

        if ($user->doctor) {
            $user->doctor->update($data);
        } else {
            Doctor::create($data);
        }

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Información del doctor actualizada correctamente.');
    }

    /**
     * Mostrar formulario para editar horarios por día
     */
    public function editSchedule(Doctor $doctor)
    {
        // asegurar que schedule sea un array
        $schedule = $doctor->schedule ?? [];

        return view('admin.doctors.schedule', compact('doctor', 'schedule'));
    }

    /**
     * Actualizar los horarios del doctor
     */
    public function updateSchedule(Request $request, Doctor $doctor)
    {
        $input = $request->input('schedule', []);

        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $normalized = [];

        $toTwentyFourHour = function (?string $hour, ?string $minute, ?string $period): ?string {
            if (!$hour || !$minute || !$period) {
                return null;
            }

            $hourNumber = (int) $hour;
            $minute = str_pad((string) $minute, 2, '0', STR_PAD_LEFT);
            $period = strtoupper($period);

            if ($period === 'AM') {
                $hourNumber = $hourNumber === 12 ? 0 : $hourNumber;
            } elseif ($period === 'PM' && $hourNumber !== 12) {
                $hourNumber += 12;
            }

            return sprintf('%02d:%s', $hourNumber, $minute);
        };

        foreach ($days as $day) {
            $dayData = $input[$day] ?? [];

            $active = filter_var($dayData['active'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $start = $toTwentyFourHour(
                $dayData['start_hour'] ?? null,
                $dayData['start_minute'] ?? null,
                $dayData['start_period'] ?? null
            );
            $end = $toTwentyFourHour(
                $dayData['end_hour'] ?? null,
                $dayData['end_minute'] ?? null,
                $dayData['end_period'] ?? null
            );

            $normalized[$day] = [
                'active' => $active,
                'start' => $active ? $start : null,
                'end' => $active ? $end : null,
            ];
        }

        $doctor->update(['schedule' => $normalized]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Horario del doctor actualizado correctamente.');
    }
}