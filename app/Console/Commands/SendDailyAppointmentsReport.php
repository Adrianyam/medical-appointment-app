<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Appointment;
use App\Mail\DailyAppointmentsReportMail;
use Illuminate\Support\Facades\Mail;

class SendDailyAppointmentsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía el reporte de citas médicas del día al administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generando reporte de citas del día...');

        $today = now()->toDateString();
        $appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->whereDate('date', $today)
            ->get();

        if ($appointments->isEmpty()) {
            $this->warn('No hay citas programadas para hoy.');
        }

        $adminEmail = config('app.admin_email', 'romycooder@gmail.com');

        Mail::to($adminEmail)->send(new DailyAppointmentsReportMail($appointments));

        $this->info("Reporte de citas enviado correctamente a: {$adminEmail}");
    }
}
