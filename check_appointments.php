<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$appointments = \Illuminate\Support\Facades\DB::table('appointments')
    ->select('id', 'date', 'start_time', 'end_time')
    ->limit(10)
    ->get();

foreach($appointments as $a) {
    echo "ID: {$a->id} | Date: {$a->date} | Start: {$a->start_time} | End: {$a->end_time}\n";
}
