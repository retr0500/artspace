<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Artwork;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Gabriel Pazos (Admin)',
            'email' => 'admin@artspace.com',
            'password' => Hash::make('admin1234'),
        ]);

        Artwork::create([
            'user_id' => $admin->id,
            'titulo' => 'Horizonte Atlántico',
            'descripcion' => 'Obra inspirada en la costa gallega con texturas densas.',
            'tecnica' => 'Óleo sobre lienzo',
            'dimensiones' => '100x80 cm',
            'precio' => 480.00,
            'destacado' => true,
        ]);

        Artwork::create([
            'user_id' => $admin->id,
            'titulo' => 'Geometría Urbana',
            'descripcion' => 'Composición abstracta de líneas y contraste cromático.',
            'tecnica' => 'Acrílico',
            'dimensiones' => '90x90 cm',
            'precio' => 350.00,
            'destacado' => false,
        ]);
    }
}