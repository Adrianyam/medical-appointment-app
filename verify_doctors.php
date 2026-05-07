<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar que los 5 usuarios médicos fueron creados
$users = \App\Models\User::whereHas('roles', function ($query) {
    $query->where('name', 'Medico');
})->orderBy('id', 'DESC')->limit(5)->get(['id', 'name', 'email']);

echo "=== Usuarios Médicos Creados ===\n\n";
foreach($users as $user) {
    echo "ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email}\n";
}
echo "\nTotal usuarios médicos: " . $users->count() . "\n";
