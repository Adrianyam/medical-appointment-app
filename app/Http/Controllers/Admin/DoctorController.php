<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index()
    {
        return view('admin.doctors.index');
    }

    public function create()
    {
        $users = User::whereDoesntHave('doctor')
            ->whereDoesntHave('patient')
            ->orderBy('name')
            ->get();

        return view('admin.doctors.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:doctors,user_id'],
            'specialization' => ['required', 'string', 'max:255'],
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
        $users = User::where(function ($query) {
            $query->whereDoesntHave('doctor')
                ->whereDoesntHave('patient');
            })
            ->orWhere(function ($query) use ($doctor) {
            $query->where('id', $doctor->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.doctors.edit', compact('doctor', 'users'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('doctors', 'user_id')->ignore($doctor->id),
            ],
            'specialization' => ['required', 'string', 'max:255'],
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
        // Esperamos recibir una estructura schedule[day] => array de franjas (ej. "08:00-08:15").
        $input = $request->input('schedule', []);

        // Normalizar: asegurarnos que cada día tenga array de slots
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $normalized = [];
        foreach ($days as $day) {
            $normalized[$day] = array_values(array_filter($input[$day] ?? []));
        }

        $doctor->update(['schedule' => $normalized]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Horario del doctor actualizado correctamente.');
    }
}