<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtworkApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_loads_correctly(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_admin_dashboard_redirects_guests_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_api_returns_artworks_and_handles_404(): void
    {
        $user = User::create([
            'name' => 'Artista Test',
            'email' => 'artista@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'blocked' => false,
        ]);

        Artwork::create([
            'user_id' => $user->id,
            'titulo' => 'Obra de Test',
            'descripcion' => 'Descripción de prueba',
            'tecnica' => 'Cuadro',
            'precio' => 250.00,
        ]);

        $this->getJson('/api/obras')
            ->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('total', 1);

        $this->getJson('/api/obras/999')
            ->assertStatus(404)
            ->assertJsonPath('status', 'error');
    }
}