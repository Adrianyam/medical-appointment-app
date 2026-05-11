<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyAdminReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $appointments,
        public string $date
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reporte diario de citas - {$this->date}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.daily-admin-report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}