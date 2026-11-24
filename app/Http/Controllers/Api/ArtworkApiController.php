<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ArtworkRepository;
use App\Services\CurrencyApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtworkApiController extends Controller
{
    public function __construct(
        private ArtworkRepository $artworkRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $obras = $this->artworkRepository->getAllForApi($request->query('buscar'));

        return response()->json([
            'status' => 'success',
            'total' => $obras->count(),
            'data' => $obras,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $obra = $this->artworkRepository->findWithAuthor($id);

        if (!$obra) {
            return response()->json([
                'status' => 'error',
                'message' => 'La obra solicitada no existe.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $obra,
        ], 200);
    }

    public function divisas(Request $request, CurrencyApiService $currencyService): JsonResponse
    {
        $eur = (float) $request->query('eur', 100);
        return response()->json([
            'status' => 'success',
            'source' => 'Frankfurter ECB Exchange API',
            'data' => $currencyService->convertFromEur($eur),
        ], 200);
    }
}