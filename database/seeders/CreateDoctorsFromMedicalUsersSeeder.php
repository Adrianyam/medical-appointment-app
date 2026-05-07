<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateDoctorsFromMedicalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los usuarios con rol Médico que no tengan un registro como doctor
        $medicalUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Medico');
        })->doesntHave('doctor')->get();

        // Especializaciones para asignar a los doctores
        $specializations = [
            'Medicina General',
            'Cardiología',
            'Dermatología',
            'Pediatría',
            'Ginecología',
        ];

        foreach ($medicalUsers as $index => $user) {
            Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $specializations[$index % count($specializations)],
                    'license_number' => 'LIC' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'information' => 'Doctor especialista en ' . $specializations[$index % count($specializations)],
                    'schedule' => null,
                ]
            );
        }
    }
}
