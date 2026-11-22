<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'artwork_id' => 'nullable|exists:artworks,id',
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'mensaje' => 'required|string|min:10',
        ]);

        Message::create($validated);

        return back()->with('success', 'Mensaje enviado correctamente al artista.');
    }
}