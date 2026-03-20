<?php

use App\Models\User;
use Tests\TestCase; // <--- Asegúrate de importar esta clase
use Illuminate\Foundation\Testing\RefreshDatabase;

// IMPORTANTE: Agrega TestCase::class aquí
uses(TestCase::class, RefreshDatabase::class);

test('Un usuario no puede eliminarse a si mismo', function() {
    // 1) crear un usuario
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    // 2) simular login
    $this->actingAs($user, 'web');

    // 3) intentar borrar
    $response = $this->delete(route('admin.users.destroy', $user));
    
    // 4) esperar error 403
    $response->assertStatus(403);

    // 5) verificar persistencia
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});