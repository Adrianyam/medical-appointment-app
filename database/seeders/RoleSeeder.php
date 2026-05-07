<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Definiir roles
        $roles = [
            'Paciente',
            'Medico',
            'Recepcionista',
            'Administrador',
            'SuperAdministrador'
        ];
        //Crear roles
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }   
    }
}