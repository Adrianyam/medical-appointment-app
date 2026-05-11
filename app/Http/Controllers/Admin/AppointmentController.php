<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentConfirmationEmailService;
use App\Services\AppointmentReceiptPdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function receipt(Appointment $appointment, AppointmentReceiptPdfService $pdfService)
    {
        return $pdfService->makePdf($appointment)->stream(sprintf('comprobante-cita-%d.pdf', $appointment->id));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.appointments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.appointments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration' => Carbon::createFromFormat('H:i', $validated['start_time'])
                ->diffInMinutes(Carbon::createFromFormat('H:i', $validated['end_time'])),
            'reason' => $validated['reason'] ?? null,
            'status' => 1,
        ]);

        $pdfPath = app(AppointmentReceiptPdfService::class)->generate($appointment);
        app(AppointmentConfirmationEmailService::class)->send($appointment, $pdfPath);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita creada exitosamente, PDF generado y correos enviados.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return redirect()->route('admin.appointments.consultation', $appointment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita eliminada exitosamente.');
    }

    /**
     * Show the consultation manager for an appointment
     */
    public function consultation(Appointment $appointment)
    {
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
