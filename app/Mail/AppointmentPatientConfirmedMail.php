<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentPatientConfirmedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $pdfPath
    ) {
        $this->appointment->loadMissing(['patient.user', 'doctor.user']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de tu cita médica'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointments.confirmation',
            text: 'emails.appointments.confirmation-text',
            with: [
                'title' => 'Tu cita médica ha sido confirmada',
                'intro' => 'Te compartimos los datos de tu cita y el comprobante en PDF adjunto para que puedas validarlos.',
                'appointment' => $this->appointment,
                'recipientLabel' => 'Paciente',
                'doctorLabel' => 'Doctor asignado',
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->pdfPath)
                ->as(sprintf('comprobante-cita-%d.pdf', $this->appointment->id))
                ->withMime('application/pdf'),
        ];
    }
}