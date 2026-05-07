<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar que los doctores fueron creados
$doctors = \App\Models\Doctor::with('user')->get();

echo "=== Doctores Creados ===\n\n";
foreach($doctors as $doctor) {
    echo "ID: {$doctor->id} | Nombre: {$doctor->user->name} | Email: {$doctor->user->email} | Especialidad: {$doctor->specialization}\n";
}
echo "\nTotal doctores: " . $doctors->count() . "\n";
