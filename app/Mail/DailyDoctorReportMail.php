<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyDoctorReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $doctor,
        public $appointments,
        public string $date
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Tus citas de hoy - {$this->date}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.daily-doctor-report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}