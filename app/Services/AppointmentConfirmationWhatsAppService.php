<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppointmentConfirmationWhatsAppService
{
    public function send(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient.user', 'doctor.user']);

        $phoneNumber = $this->normalizePhoneNumber($appointment->patient?->user?->number_phone);

        if (!$phoneNumber) {
            return;
        }

        $instanceId = config('services.ultramsg.instance_id');
        $token = config('services.ultramsg.token');

        if (!$instanceId || !$token) {
            Log::warning('UltraMsg no está configurado correctamente para WhatsApp.', [
                'appointment_id' => $appointment->id,
            ]);

            return;
        }

        $message = $this->buildMessage($appointment);

        $response = Http::asForm()->post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
            'token' => $token,
            'to' => $phoneNumber,
            'body' => $message,
            'priority' => 10,
            'referenceId' => 'appointment-' . $appointment->id,
        ]);

        if (!$response->successful()) {
            Log::warning('No se pudo enviar WhatsApp de confirmación.', [
                'appointment_id' => $appointment->id,
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        } else {
            Log::info('WhatsApp de confirmación enviado', [
                'appointment_id' => $appointment->id,
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        }
    }

    public function sendReminder(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient.user', 'doctor.user']);

        $phoneNumber = $this->normalizePhoneNumber($appointment->patient?->user?->number_phone);

        if (!$phoneNumber) {
            Log::warning('No se pudo enviar recordatorio por WhatsApp: el paciente no tiene teléfono.', [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
            ]);

            return;
        }

        $instanceId = config('services.ultramsg.instance_id');
        $token = config('services.ultramsg.token');

        if (!$instanceId || !$token) {
            Log::warning('UltraMsg no está configurado correctamente para recordatorios de WhatsApp.', [
                'appointment_id' => $appointment->id,
            ]);

            return;
        }

        $message = $this->buildReminderMessage($appointment);

        $response = Http::asForm()->post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
            'token' => $token,
            'to' => $phoneNumber,
            'body' => $message,
            'priority' => 10,
            'referenceId' => 'appointment-reminder-' . $appointment->id,
        ]);

        if (!$response->successful()) {
            Log::warning('No se pudo enviar WhatsApp de recordatorio.', [
                'appointment_id' => $appointment->id,
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        } else {
            Log::info('WhatsApp de recordatorio enviado', [
                'appointment_id' => $appointment->id,
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        }
    }

    private function buildMessage(Appointment $appointment): string
    {
        $date = $appointment->date?->format('d/m/Y') ?? (string) $appointment->date;
        $startTime = substr((string) $appointment->start_time, 0, 5);
        $endTime = substr((string) $appointment->end_time, 0, 5);

        return "Hola {$appointment->patient?->user?->name}, tu cita ya fue agendada correctamente.\n\n"
            . "Detalle de la cita:\n"
            . "- Fecha: {$date}\n"
            . "- Hora: {$startTime} - {$endTime}\n"
            . "- Doctor: {$appointment->doctor?->user?->name}\n"
            . "- Especialidad: {$appointment->doctor?->specialization}\n\n"
            . "También te enviamos el comprobante en PDF por correo electrónico.";
    }

    private function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (!$phoneNumber) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $phoneNumber);

        // Si el número tiene 10 dígitos (común en MX/CO), anteponemos el código de país
        // Puedes cambiar '52' por el código de tu país si es diferente
        if (strlen($normalized) === 10) {
            $normalized = '52' . $normalized;
        }

        return $normalized ?: null;
    }

    private function buildReminderMessage(Appointment $appointment): string
    {
        $date = $appointment->date?->format('d/m/Y') ?? (string) $appointment->date;
        $startTime = substr((string) $appointment->start_time, 0, 5);
        $endTime = substr((string) $appointment->end_time, 0, 5);

        return "⏰ Recordatorio de tu cita médica\n\n"
            . "Hola {$appointment->patient?->user?->name}, te recordamos que tu cita es mañana.\n\n"
            . "Detalle de la cita:\n"
            . "- Fecha: {$date}\n"
            . "- Hora: {$startTime} - {$endTime}\n"
            . "- Doctor: {$appointment->doctor?->user?->name}\n"
            . "- Especialidad: {$appointment->doctor?->specialization}\n\n"
            . "Por favor llega con 10 minutos de anticipación.";
    }
}