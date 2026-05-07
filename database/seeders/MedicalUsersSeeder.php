<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 5 usuarios con rol de Médico para pruebas
        $doctors = [
            ['name' => 'Dr. Juan Pérez', 'email' => 'juan@gmail.com'],
            ['name' => 'Dra. María López', 'email' => 'maria@gmail.com'],
            ['name' => 'Dr. Carlos García', 'email' => 'carlos@gmail.com'],
            ['name' => 'Dra. Laura Martínez', 'email' => 'laura@gmail.com'],
            ['name' => 'Dr. Roberto Sánchez', 'email' => 'roberto@gmail.com'],
        ];

        foreach ($doctors as $doctorData) {
            $user = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => bcrypt('12345678'),
                    'id_number' => sprintf('DOC%05d', rand(10000, 99999)),
                    'number_phone' => '3000000000',
                    'address' => 'Hospital General',
                ]
            );

            // Asignar rol de Médico
            $user->syncRoles(['Medico']);
        }
    }
}
