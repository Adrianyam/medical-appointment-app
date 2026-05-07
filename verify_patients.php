<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar que solo se cargan pacientes con rol 'Paciente'
$patients = \App\Models\Patient::with('user')
    ->whereHas('user.roles', function ($query) {
        $query->where('name', 'Paciente');
    })
    ->orderBy('id')
    ->get();

echo "=== Pacientes con Rol Paciente ===\n\n";
foreach($patients as $patient) {
    echo "ID: {$patient->id} | Nombre: {$patient->user->name} | Email: {$patient->user->email}\n";
}
echo "\nTotal pacientes (solo rol Paciente): " . $patients->count() . "\n";
