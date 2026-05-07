<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Hacer exactamente lo que hace AppointmentTable
$appointments = \App\Models\Appointment::query()->with(['patient.user', 'doctor.user'])->get();

echo "Total appointments: " . count($appointments) . "\n\n";

foreach($appointments as $apt) {
    echo "ID: {$apt->id} | Patient: {$apt->patient->user->name} | Doctor: {$apt->doctor->user->name}\n";
    echo "  Date: {$apt->date} | Start: {$apt->start_time} | End: {$apt->end_time}\n";
    echo "  Start_time bool: " . ($apt->start_time ? 'TRUE' : 'FALSE') . " | End_time bool: " . ($apt->end_time ? 'TRUE' : 'FALSE') . "\n\n";
}
