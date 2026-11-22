<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Repositories\ArtworkRepository;
use App\Services\MuseumApiService;
use Illuminate\Http\Request;

class ArtworkController extends Controller
{
    public function __construct(
        private ArtworkRepository $artworkRepository
    ) {}

    public function index(Request $request, MuseumApiService $museumService)
    {
        $obras = $this->artworkRepository->getFilteredPaginated(
            $request->only(['buscar', 'tecnica', 'orden']),
            6
        );
        $inspiracion = $museumService->getInspiracionObras(3);

        return view('obras.index', compact('obras', 'inspiracion'));
    }

    public function show($id)
    {
        $obra = Artwork::with('user')->findOrFail($id);
        return view('obras.show', compact('obra'));
    }
}