<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Imagen', 'Cuadro', 'Vídeo'] as $cat) {
            Category::firstOrCreate(['nombre' => $cat]);
        }

        foreach (SiteSetting::getAllAsArray() as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $admin = User::create([
            'name' => 'Gabriel Pazos (Artista)',
            'email' => 'admin@artspace.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'blocked' => false,
        ]);

        User::create([
            'name' => 'Laura Martínez',
            'email' => 'cliente@artspace.com',
            'password' => Hash::make('cliente1234'),
            'role' => 'user',
            'blocked' => false,
        ]);

        Artwork::create([
            'user_id' => $admin->id,
            'titulo' => 'Horizonte Atlántico',
            'descripcion' => 'Obra inspirada en la costa gallega con texturas densas y luz natural.',
            'tecnica' => 'Cuadro',
            'dimensiones' => '100x80 cm',
            'precio' => 480.00,
            'tipo_archivo' => 'imagen',
            'destacado' => true,
            'nuevo_hasta' => now()->addDay(), // Activo durante 24 horas reales
        ]);

        Artwork::create([
            'user_id' => $admin->id,
            'titulo' => 'Geometría Urbana',
            'descripcion' => 'Composición fotográfica y digital de líneas arquitectónicas.',
            'tecnica' => 'Imagen',
            'dimensiones' => '90x90 cm',
            'precio' => 350.00,
            'tipo_archivo' => 'imagen',
            'destacado' => false,
            'nuevo_hasta' => null,
        ]);
    }
}