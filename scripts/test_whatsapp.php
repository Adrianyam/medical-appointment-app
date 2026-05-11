<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appointment;
use App\Services\AppointmentConfirmationWhatsAppService;

$appointment = Appointment::latest()->first();

if ($appointment) {
    echo "Probando envío de WhatsApp para cita #{$appointment->id}...\n";
    echo "Paciente: {$appointment->patient->user->name}\n";
    echo "Teléfono: {$appointment->patient->user->number_phone}\n";
    
    try {
        app(AppointmentConfirmationWhatsAppService::class)->send($appointment);
        echo "Llamada al servicio completada. Revisa los logs en storage/logs/laravel.log\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No se encontró ninguna cita para probar.\n";
}
