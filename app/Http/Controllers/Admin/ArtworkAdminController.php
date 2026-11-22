<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtworkAdminController extends Controller
{
    public function index()
    {
        $obras = Artwork::where('user_id', Auth::id())->latest()->get();
        $mensajesCount = Message::where('leido', false)->count();
        return view('admin.index', compact('obras', 'mensajesCount'));
    }

    public function create()
    {
        return view('admin.obras.crear');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'tecnica' => 'required|string|max:100',
            'dimensiones' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('obras', 'public');
            $data['imagen'] = $path;
        }

        $data['user_id'] = Auth::id();
        $data['destacado'] = $request->has('destacado');

        Artwork::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Obra creada correctamente.');
    }

    public function edit(Artwork $artwork)
    {
        // Regla de autorización: solo el propietario puede editar
        abort_if($artwork->user_id !== Auth::id(), 403, 'No tienes permiso para editar esta obra.');
        return view('admin.obras.editar', compact('artwork'));
    }

    public function update(Request $request, Artwork $artwork)
    {
        abort_if($artwork->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'tecnica' => 'required|string|max:100',
            'dimensiones' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('obras', 'public');
        }

        $data['destacado'] = $request->has('destacado');
        $artwork->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Obra actualizada.');
    }

    public function destroy(Artwork $artwork)
    {
        abort_if($artwork->user_id !== Auth::id(), 403);
        $artwork->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Obra eliminada.');
    }

    public function mensajes()
    {
        $mensajes = Message::with('artwork')->latest()->get();
        return view('admin.mensajes', compact('mensajes'));
    }
}