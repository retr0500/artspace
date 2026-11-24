<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getAllAsArray(): array
    {
        $defaults = [
            'site_title' => 'ArtSpace',
            'hero_title' => 'Explora Arte Independiente',
            'hero_subtitle' => 'Conecta directamente con creadores locales sin intermediarios.',
            'footer_text' => 'ArtSpace — Plataforma de Arte Independiente | Proyecto DAW - IES Fernando Wirtz Suárez',
            'logo_path' => '',
            'color_primary' => '#1e293b',
            'color_secondary' => '#b45309',
            'color_bg' => '#f8fafc',
            'ai_protection' => '1',
        ];

        try {
            $stored = static::pluck('value', 'key')->toArray();
            return array_merge($defaults, $stored);
        } catch (\Throwable $e) {
            return $defaults;
        }
    }
}