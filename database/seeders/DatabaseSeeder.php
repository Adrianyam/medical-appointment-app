<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // LLamar a los seeders creados
        $this->call([RoleSeeder::class]);

        //crear un usuarios de prueba cada vez que se hagan migraciones
        User::factory()->create([
            'name' => 'prueba',
            'email' => 'prueba@gmai.com',
            'Password' => bcrypt('12345678')
        ]);
    }
}
