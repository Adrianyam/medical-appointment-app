<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //crear un usuarios de prueba cada vez que se hagan migraciones
        User::factory()->create([
            'name' => 'prueba',
            'email' => 'prueba@gmail.com',
            'password' => bcrypt('12345678'),
            'id_number' => '123456789',
            'number_phone' => '1234567890',
            'address' => 'Calle Falsa 123',
        ])->assignRole('Administrador'); //asignar el rol de admin a este usuario
    }
}
