<?php

use App\Mail\DailyAdminReportMail;
use App\Mail\DailyDoctorReportMail;
use App\Models\Appointment;
use App\Services\AppointmentConfirmationWhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('appointments:send-reminders', function () {
    $whatsAppService = app(AppointmentConfirmationWhatsAppService::class);
    $now = Carbon::now();
    $targetTime = $now->copy()->addMinutes(5)->format('H:i');
    $today = $now->toDateString();

    $appointments = Appointment::query()
        ->with(['patient.user', 'doctor.user'])
        ->whereDate('date', $today)
        ->where('start_time', $targetTime)
        ->orderBy('start_time')
        ->get();

    if ($appointments->isEmpty()) {
        $this->info("No hay citas a las {$targetTime} para hoy.");
        return Command::SUCCESS;
    }

    foreach ($appointments as $appointment) {
        $whatsAppService->sendReminder($appointment);
        $this->info("Recordatorio enviado para la cita #{$appointment->id}.");
    }

    return Command::SUCCESS;
})->purpose('Envía recordatorios por WhatsApp 5 minutos antes de la cita');

Artisan::command('appointments:send-daily-report', function () {
    $today = Carbon::today()->toDateString();
    $appointments = Appointment::query()
        ->with(['patient.user', 'doctor.user'])
        ->whereDate('date', $today)
        ->orderBy('start_time')
        ->get();

    if ($appointments->isEmpty()) {
        $this->info('No hay citas para hoy.');

        return Command::SUCCESS;
    }

    $dateFormatted = Carbon::today()->translatedFormat('d \d\e F \d\e Y');
    $adminEmail = config('app.admin_email');

    Mail::to($adminEmail)->send(new DailyAdminReportMail($appointments, $dateFormatted));

    $appointments
        ->pluck('doctor')
        ->filter()
        ->unique('id')
        ->each(function ($doctor) use ($appointments, $dateFormatted) {
            $doctorAppointments = $appointments->where('doctor_id', $doctor->id)->values();

            if ($doctor?->user?->email) {
                Mail::to($doctor->user->email)->send(
                    new DailyDoctorReportMail($doctor, $doctorAppointments, $dateFormatted)
                );
            }
        });

    $this->info('Reporte diario enviado al administrador y a los doctores con citas hoy.');

    return Command::SUCCESS;
})->purpose('Envía el reporte diario de citas por correo');

Schedule::command('appointments:send-reminders')
    ->everyMinute()
    ->timezone(config('app.timezone'))
    ->withoutOverlapping();

Schedule::command('appointments:send-daily-report')
    ->dailyAt('21:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping();
