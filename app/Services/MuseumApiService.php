<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MuseumApiService
{
    private string $baseUrl = 'https://api.artic.edu/api/v1';

    public function getInspiracionObras(int $limit = 3): array
    {
        return Cache::remember("museum_inspiration_{$limit}", 3600, function () use ($limit) {
            try {
                $response = Http::timeout(5)
                    ->retry(2, 200)
                    ->get("{$this->baseUrl}/artworks/search", [
                        'q' => 'landscape painting',
                        'limit' => $limit,
                        'fields' => 'id,title,artist_display,date_display,medium_display,image_id',
                    ]);

                $items = $response->json('data', []);

                return array_map(function ($item) {
                    $imageId = $item['image_id'] ?? null;

                    return [
                        'external_id' => $item['id'] ?? null,
                        'titulo' => $item['title'] ?? 'Sin título',
                        'artista' => $item['artist_display'] ?? 'Autor desconocido',
                        'fecha' => $item['date_display'] ?? 'Fecha no disponible',
                        'tecnica' => $item['medium_display'] ?? 'Técnica mixta',
                        'imagen_url' => $imageId
                            ? "https://www.artic.edu/iiif/2/{$imageId}/full/400,/0/default.jpg"
                            : null,
                    ];
                }, $items);
            } catch (\Throwable $e) {
                Log::error('Error al consultar Art Institute of Chicago API: ' . $e->getMessage());
                return $this->getFallbackObras();
            }
        });
    }

    private function getFallbackObras(): array
    {
        return [
            [
                'external_id' => 0,
                'titulo' => 'Impresión, sol naciente (Modo sin conexión)',
                'artista' => 'Claude Monet',
                'fecha' => '1872',
                'tecnica' => 'Óleo sobre lienzo',
                'imagen_url' => null,
            ],
        ];
    }
}