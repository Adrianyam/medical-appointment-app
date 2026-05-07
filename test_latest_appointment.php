<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ver exactamente qué se está guardando
$latestAppointment = \App\Models\Appointment::query()
    ->orderBy('created_at', 'DESC')
    ->first();

echo "=== Latest Appointment ===\n";
echo "ID: {$latestAppointment->id}\n";
echo "Patient: {$latestAppointment->patient->user->name}\n";
echo "Doctor: {$latestAppointment->doctor->user->name}\n";
echo "Date: {$latestAppointment->date}\n";
echo "Start Time: '{$latestAppointment->start_time}' (null? " . ($latestAppointment->start_time === null ? 'YES' : 'NO') . ")\n";
echo "End Time: '{$latestAppointment->end_time}' (null? " . ($latestAppointment->end_time === null ? 'YES' : 'NO') . ")\n";
echo "Created at: {$latestAppointment->created_at}\n";
echo "Updated at: {$latestAppointment->updated_at}\n";

// Verificar exactamente cuál es el último ID
echo "\n=== All Appointments ordered by ID DESC ===\n";
$all = \App\Models\Appointment::query()->orderBy('id', 'DESC')->get(['id', 'date', 'start_time', 'end_time']);
foreach($all as $apt) {
    echo "ID: {$apt->id} | Date: {$apt->date} | Start: {$apt->start_time} | End: {$apt->end_time}\n";
}
