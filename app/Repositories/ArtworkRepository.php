<?php

namespace App\Repositories;

use App\Models\Artwork;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ArtworkRepository
{
    public function getFilteredPaginated(array $filters, int $perPage = 6): LengthAwarePaginator
    {
        $query = Artwork::with('user');

        if (!empty($filters['buscar'])) {
            $query->where('titulo', 'like', '%' . $filters['buscar'] . '%');
        }

        if (!empty($filters['tecnica'])) {
            $query->where('tecnica', $filters['tecnica']);
        }

        $orden = $filters['orden'] ?? 'recientes';
        if ($orden === 'precio_asc') {
            $query->orderBy('precio', 'asc');
        } elseif ($orden === 'precio_desc') {
            $query->orderBy('precio', 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getAllForApi(?string $buscar = null): Collection
    {
        $query = Artwork::with('user:id,name');

        if (!empty($buscar)) {
            $query->where('titulo', 'like', '%' . $buscar . '%');
        }

        return $query->latest()->get();
    }

    public function findWithAuthor(int $id): ?Artwork
    {
        return Artwork::with('user:id,name')->find($id);
    }
}