<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appointment;

$id = 24;
$a = Appointment::find($id);
if (! $a) {
    echo "no-appointment\n";
    exit(1);
}

$pdf = app(App\Services\AppointmentReceiptPdfService::class)->generate($a);
app(App\Services\AppointmentConfirmationEmailService::class)->send($a, $pdf);

echo "done\n";
