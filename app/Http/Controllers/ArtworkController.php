<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use App\Repositories\ArtworkRepository;
use App\Services\CurrencyApiService;
use Illuminate\Http\Request;

class ArtworkController extends Controller
{
    public function __construct(
        private ArtworkRepository $artworkRepository
    ) {}

    public function index(Request $request)
    {
        $obras = $this->artworkRepository->getFilteredPaginated(
            $request->only(['buscar', 'tecnica', 'orden']),
            6
        );
        $categorias = Category::orderBy('id')->get();

        return view('obras.index', compact('obras', 'categorias'));
    }

    public function show($id, CurrencyApiService $currencyService)
    {
        $obra = Artwork::with('user')->findOrFail($id);
        $divisas = $currencyService->convertFromEur((float) $obra->precio);

        return view('obras.show', compact('obra', 'divisas'));
    }

    public function favoritas()
    {
        return view('obras.favoritas');
    }
}