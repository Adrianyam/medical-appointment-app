<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar citas que tengan NULL en date con la fecha actual
        DB::statement("UPDATE appointments SET date = CURDATE() WHERE date IS NULL");
        
        // Actualizar citas que tengan NULL en start_time con 08:00
        DB::statement("UPDATE appointments SET start_time = '08:00' WHERE start_time IS NULL");
        
        // Actualizar citas que tengan NULL en end_time con 08:15
        DB::statement("UPDATE appointments SET end_time = '08:15' WHERE end_time IS NULL");
        
        // Actualizar duration para citas que no lo tengan (15 minutos por defecto)
        DB::statement("UPDATE appointments SET duration = 15 WHERE duration IS NULL OR duration = 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertir datos, solo dejar como estaban
    }
};
