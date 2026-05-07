<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Luis Torres', 'email' => 'doctor1@gmail.com', 'id_number' => 'DOC0001', 'number_phone' => '3000000001', 'address' => 'Calle 1 #10-01', 'specialization' => 'Medicina General', 'license_number' => 'LIC-1001', 'information' => 'Atiende consulta general, control de enfermedades crónicas y seguimiento de tratamientos básicos.'],
            ['name' => 'Dr. Carlos Perez', 'email' => 'doctor2@gmail.com', 'id_number' => 'DOC0002', 'number_phone' => '3000000002', 'address' => 'Calle 2 #10-02', 'specialization' => 'Cardiología', 'license_number' => 'LIC-1002', 'information' => 'Enfocado en evaluación cardiovascular, hipertensión y control preventivo.'],
            ['name' => 'Dr. Ana Rojas', 'email' => 'doctor3@gmail.com', 'id_number' => 'DOC0003', 'number_phone' => '3000000003', 'address' => 'Calle 3 #10-03', 'specialization' => 'Pediatría', 'license_number' => 'LIC-1003', 'information' => 'Atención integral de pacientes pediátricos, control de crecimiento y vacunación.'],
            ['name' => 'Dr. Miguel Santos', 'email' => 'doctor4@gmail.com', 'id_number' => 'DOC0004', 'number_phone' => '3000000004', 'address' => 'Calle 4 #10-04', 'specialization' => 'Dermatología', 'license_number' => 'LIC-1004', 'information' => 'Diagnóstico y tratamiento de enfermedades de la piel, cabello y uñas.'],
            ['name' => 'Dra. Laura Gomez', 'email' => 'doctor5@gmail.com', 'id_number' => 'DOC0005', 'number_phone' => '3000000005', 'address' => 'Calle 5 #10-05', 'specialization' => 'Ginecología', 'license_number' => 'LIC-1005', 'information' => 'Control ginecológico, salud reproductiva y seguimiento prenatal.'],
            ['name' => 'Dr. Javier Molina', 'email' => 'doctor6@gmail.com', 'id_number' => 'DOC0006', 'number_phone' => '3000000006', 'address' => 'Calle 6 #10-06', 'specialization' => 'Ortopedia', 'license_number' => 'LIC-1006', 'information' => 'Evaluación de lesiones musculoesqueléticas, fracturas y dolor articular.'],
            ['name' => 'Dra. Paula Herrera', 'email' => 'doctor7@gmail.com', 'id_number' => 'DOC0007', 'number_phone' => '3000000007', 'address' => 'Calle 7 #10-07', 'specialization' => 'Neurología', 'license_number' => 'LIC-1007', 'information' => 'Consulta neurológica para cefaleas, mareos, migraña y trastornos del sistema nervioso.'],
            ['name' => 'Dr. Andres Cardenas', 'email' => 'doctor8@gmail.com', 'id_number' => 'DOC0008', 'number_phone' => '3000000008', 'address' => 'Calle 8 #10-08', 'specialization' => 'Urología', 'license_number' => 'LIC-1008', 'information' => 'Atención de patologías urinarias y salud del sistema genitourinario.'],
            ['name' => 'Dra. Sofia Vargas', 'email' => 'doctor9@gmail.com', 'id_number' => 'DOC0009', 'number_phone' => '3000000009', 'address' => 'Calle 9 #10-09', 'specialization' => 'Psiquiatría', 'license_number' => 'LIC-1009', 'information' => 'Evaluación y tratamiento de trastornos del estado de ánimo, ansiedad y estrés.'],
            ['name' => 'Dr. Daniel Ruiz', 'email' => 'doctor10@gmail.com', 'id_number' => 'DOC0010', 'number_phone' => '3000000010', 'address' => 'Calle 10 #10-10', 'specialization' => 'Otorrinolaringología', 'license_number' => 'LIC-1010', 'information' => 'Manejo de problemas de oído, nariz y garganta.'],
            ['name' => 'Dra. Maria Peña', 'email' => 'doctor11@gmail.com', 'id_number' => 'DOC0011', 'number_phone' => '3000000011', 'address' => 'Calle 11 #10-11', 'specialization' => 'Endocrinología', 'license_number' => 'LIC-1011', 'information' => 'Control de diabetes, tiroides y trastornos hormonales.'],
            ['name' => 'Dr. Felipe Castro', 'email' => 'doctor12@gmail.com', 'id_number' => 'DOC0012', 'number_phone' => '3000000012', 'address' => 'Calle 12 #10-12', 'specialization' => 'Gastroenterología', 'license_number' => 'LIC-1012', 'information' => 'Atención de enfermedades digestivas, hígado y sistema gastrointestinal.'],
            ['name' => 'Dra. Camila Ortiz', 'email' => 'doctor13@gmail.com', 'id_number' => 'DOC0013', 'number_phone' => '3000000013', 'address' => 'Calle 13 #10-13', 'specialization' => 'Neumología', 'license_number' => 'LIC-1013', 'information' => 'Tratamiento de asma, bronquitis y enfermedades respiratorias.'],
            ['name' => 'Dr. Sebastian Leon', 'email' => 'doctor14@gmail.com', 'id_number' => 'DOC0014', 'number_phone' => '3000000014', 'address' => 'Calle 14 #10-14', 'specialization' => 'Medicina Interna', 'license_number' => 'LIC-1014', 'information' => 'Seguimiento clínico de pacientes adultos y manejo integral de patologías complejas.'],
            ['name' => 'Dra. Valeria Cruz', 'email' => 'doctor15@gmail.com', 'id_number' => 'DOC0015', 'number_phone' => '3000000015', 'address' => 'Calle 15 #10-15', 'specialization' => 'Oftalmología', 'license_number' => 'LIC-1015', 'information' => 'Valoración visual, control ocular y detección temprana de enfermedades de la vista.'],
        ];

        foreach ($doctors as $doctorData) {
            $user = User::updateOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => bcrypt('12345678'),
                    'id_number' => $doctorData['id_number'],
                    'number_phone' => $doctorData['number_phone'],
                    'address' => $doctorData['address'],
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $doctorData['specialization'],
                    'license_number' => $doctorData['license_number'],
                    'information' => $doctorData['information'],
                ]
            );
        }
    }
}