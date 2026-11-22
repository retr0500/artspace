<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ArtworkAdminController;
use App\Http\Controllers\Api\ArtworkApiController;

// Rutas públicas
Route::get('/', [ArtworkController::class, 'index'])->name('home');
Route::get('/obra/{id}', [ArtworkController::class, 'show'])->name('obras.show');
Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactController::class, 'store'])->name('contacto.store');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Panel de administración protegido
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [ArtworkAdminController::class, 'index'])->name('dashboard');
    Route::get('/obras/crear', [ArtworkAdminController::class, 'create'])->name('obras.create');
    Route::post('/obras', [ArtworkAdminController::class, 'store'])->name('obras.store');
    Route::get('/obras/{artwork}/editar', [ArtworkAdminController::class, 'edit'])->name('obras.edit');
    Route::put('/obras/{artwork}', [ArtworkAdminController::class, 'update'])->name('obras.update');
    Route::delete('/obras/{artwork}', [ArtworkAdminController::class, 'destroy'])->name('obras.destroy');
    Route::get('/mensajes', [ArtworkAdminController::class, 'mensajes'])->name('mensajes');
});

// Endpoints API REST (JSON)
Route::prefix('api')->group(function () {
    Route::get('/obras', [ArtworkApiController::class, 'index'])->name('api.obras.index');
    Route::get('/obras/{id}', [ArtworkApiController::class, 'show'])->name('api.obras.show');
    Route::get('/inspiracion', [ArtworkApiController::class, 'inspiracion'])->name('api.inspiracion');
});