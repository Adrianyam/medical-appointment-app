<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Patient;
use App\Mail\PatientsListReportMail;
use Illuminate\Support\Facades\Mail;

class SendPatientsListReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-patients-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía la lista completa de pacientes al correo del administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Consultando lista de pacientes...');
        
        $patients = Patient::with('user')->get();

        if ($patients->isEmpty()) {
            $this->warn('No hay pacientes registrados.');
            return;
        }

        $adminEmail = config('app.admin_email', 'romycooder@gmail.com');

        Mail::to($adminEmail)->send(new PatientsListReportMail($patients));

        $this->info("Lista de pacientes enviada correctamente a: {$adminEmail}");
    }
}
