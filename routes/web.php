<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ArtworkAdminController;
use App\Http\Controllers\Api\ArtworkApiController;


Route::get('/', [ArtworkController::class, 'index'])->name('home');
Route::get('/favoritas', [ArtworkController::class, 'favoritas'])->name('favoritas');
Route::get('/obra/{id}', [ArtworkController::class, 'show'])->name('obras.show');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
    Route::post('/contacto', [ContactController::class, 'store'])->name('contacto.store');
    Route::get('/mis-mensajes', [ContactController::class, 'myMessages'])->name('mis-mensajes');
});


Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [ArtworkAdminController::class, 'index'])->name('dashboard');
    Route::post('/modo-edicion', [ArtworkAdminController::class, 'toggleEditMode'])->name('edit-mode');
    Route::post('/ajustes', [ArtworkAdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/categorias', [ArtworkAdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categorias/{category}', [ArtworkAdminController::class, 'destroyCategory'])->name('categories.destroy');

    Route::get('/obras/crear', [ArtworkAdminController::class, 'create'])->name('obras.create');
    Route::post('/obras', [ArtworkAdminController::class, 'store'])->name('obras.store');
    Route::get('/obras/{artwork}/editar', [ArtworkAdminController::class, 'edit'])->name('obras.edit');
    Route::put('/obras/{artwork}', [ArtworkAdminController::class, 'update'])->name('obras.update');
    Route::delete('/obras/{artwork}', [ArtworkAdminController::class, 'destroy'])->name('obras.destroy');

    Route::get('/mensajes', [ArtworkAdminController::class, 'mensajes'])->name('mensajes');
    Route::post('/mensajes/{message}/responder', [ArtworkAdminController::class, 'replyMessage'])->name('mensajes.reply');
    Route::delete('/mensajes/{message}', [ArtworkAdminController::class, 'destroyMessage'])->name('mensajes.destroy');
    Route::post('/usuarios/{user}/bloquear', [ArtworkAdminController::class, 'toggleBlockUser'])->name('usuarios.block');
});


Route::prefix('api')->group(function () {
    Route::get('/obras', [ArtworkApiController::class, 'index'])->name('api.obras.index');
    Route::get('/obras/{id}', [ArtworkApiController::class, 'show'])->name('api.obras.show');
    Route::get('/divisas', [ArtworkApiController::class, 'divisas'])->name('api.divisas');
});