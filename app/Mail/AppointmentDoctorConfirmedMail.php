<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentDoctorConfirmedMail extends Mailable
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
            subject: 'Nueva cita médica confirmada'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointments.confirmation',
            text: 'emails.appointments.confirmation-text',
            with: [
                'title' => 'Se ha asignado una nueva cita médica',
                'intro' => 'Se confirmó una cita asociada a tu agenda. Revisa los datos y el comprobante adjunto en PDF.',
                'appointment' => $this->appointment,
                'recipientLabel' => 'Paciente',
                'doctorLabel' => 'Doctor',
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