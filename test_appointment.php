<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Obtener la cita 4
$appointment = \App\Models\Appointment::with(['patient.user', 'doctor.user'])->find(4);

echo "Appointment ID: " . $appointment->id . "\n";
echo "Date: " . $appointment->date . " (Type: " . gettype($appointment->date) . ")\n";
echo "Start Time: " . $appointment->start_time . " (Type: " . gettype($appointment->start_time) . ")\n";
echo "End Time: " . $appointment->end_time . " (Type: " . gettype($appointment->end_time) . ")\n";

if ($appointment->date) {
    echo "Date formatted: " . $appointment->date->format('d/m/Y') . "\n";
}

if ($appointment->start_time && $appointment->end_time) {
    echo "Time range: " . $appointment->start_time . ' - ' . $appointment->end_time . "\n";
}
