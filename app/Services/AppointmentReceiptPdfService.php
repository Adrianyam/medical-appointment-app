<?php

namespace App\Services;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class AppointmentReceiptPdfService
{
    public function makePdf(Appointment $appointment)
    {
        $appointment->loadMissing(['patient.user', 'doctor.user']);

        return Pdf::loadView('pdf.appointment-receipt', [
            'appointment' => $appointment,
        ])->setPaper('a4');
    }

    public function generate(Appointment $appointment): string
    {
        $pdf = $this->makePdf($appointment);

        $fileName = sprintf('citas/comprobante-cita-%d.pdf', $appointment->id);

        Storage::disk('public')->put($fileName, $pdf->output());

        return $fileName;
    }
}