<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simular exactamente lo que hace Livewire DataTable
$appointments = \App\Models\Appointment::query()
    ->with(['patient.user', 'doctor.user'])
    ->get();

echo "=== Testing Column Logic ===\n\n";

foreach($appointments as $row) {
    echo "ID: {$row->id}\n";
    echo "  start_time value: '{$row->start_time}'\n";
    echo "  end_time value: '{$row->end_time}'\n";
    echo "  start_time is truthy: " . (($row->start_time) ? 'YES' : 'NO') . "\n";
    echo "  end_time is truthy: " . (($row->end_time) ? 'YES' : 'NO') . "\n";
    echo "  Both truthy: " . (($row->start_time && $row->end_time) ? 'YES' : 'NO') . "\n";
    
    if ($row->start_time && $row->end_time) {
        echo "  Result: " . $row->start_time . ' - ' . $row->end_time . "\n";
    } else {
        echo "  Result: -\n";
    }
    echo "\n";
}
