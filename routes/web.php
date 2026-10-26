<?php
   use Illuminate\Support\Facades\Route;
   use App\Http\Controllers\ArtworkController;

   Route::get('/', [ArtworkController::class, 'index']);
   Route::post('/guardar-obra', [ArtworkController::class, 'store']);