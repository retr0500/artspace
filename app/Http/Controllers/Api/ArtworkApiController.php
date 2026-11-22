<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ArtworkRepository;
use App\Services\MuseumApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArtworkApiController extends Controller
{
    public function __construct(
        private ArtworkRepository $artworkRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $obras = $this->artworkRepository->getAllForApi($request->query('buscar'));
        Log::info('Consulta API GET /api/obras', ['total' => $obras->count()]);

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
            Log::warning('Obra no encontrada en API', ['id' => $id]);

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

    public function inspiracion(MuseumApiService $museumService): JsonResponse
    {
        $obrasExternas = $museumService->getInspiracionObras(3);

        return response()->json([
            'status' => 'success',
            'source' => 'Art Institute of Chicago API',
            'data' => $obrasExternas,
        ], 200);
    }
}