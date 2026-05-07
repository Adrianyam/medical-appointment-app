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
        $user = User::firstOrCreate([
            'email' => 'prueba@gmail.com',
        ], [
            'name' => 'prueba',
            'password' => bcrypt('12345678'),
            'id_number' => '123456789',
            'number_phone' => '1234567890',
            'address' => 'Calle Falsa 123',
        ]);

        $user->syncRoles(['Administrador']); //asignar el rol de admin a este usuario
    }
}
