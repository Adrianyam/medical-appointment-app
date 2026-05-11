<?php

namespace App\Services;

use App\Mail\AppointmentDoctorConfirmedMail;
use App\Mail\AppointmentPatientConfirmedMail;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentConfirmationEmailService
{
    public function send(Appointment $appointment, string $pdfPath): void
    {
        $appointment->loadMissing(['patient.user', 'doctor.user']);

        $patientEmail = $appointment->patient?->user?->email;
        $doctorEmail = $appointment->doctor?->user?->email;

        if ($patientEmail) {
            try {
                Mail::to($patientEmail)->send(new AppointmentPatientConfirmedMail($appointment, $pdfPath));
                    Log::info('Correo de confirmación enviado al paciente', [
                        'appointment_id' => $appointment->id,
                        'email' => $patientEmail,
                    ]);
            } catch (\Throwable $e) {
                Log::error('Error al enviar correo al paciente', [
                    'appointment_id' => $appointment->id,
                    'email' => $patientEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($doctorEmail) {
            try {
                Mail::to($doctorEmail)->send(new AppointmentDoctorConfirmedMail($appointment, $pdfPath));
                    Log::info('Correo de confirmación enviado al doctor', [
                        'appointment_id' => $appointment->id,
                        'email' => $doctorEmail,
                    ]);
            } catch (\Throwable $e) {
                Log::error('Error al enviar correo al doctor', [
                    'appointment_id' => $appointment->id,
                    'email' => $doctorEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Intentar enviar WhatsApp aunque fallen los correos
        try {
            app(AppointmentConfirmationWhatsAppService::class)->send($appointment);
        } catch (\Throwable $e) {
            Log::warning('Error al intentar enviar WhatsApp de confirmación', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}