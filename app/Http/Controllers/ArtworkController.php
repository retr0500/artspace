<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $query = Artwork::with('user');

        if ($request->filled('buscar')) {
            $query->where('titulo', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('tecnica')) {
            $query->where('tecnica', $request->tecnica);
        }

        $orden = $request->get('orden', 'recientes');
        if ($orden === 'precio_asc') {
            $query->orderBy('precio', 'asc');
        } elseif ($orden === 'precio_desc') {
            $query->orderBy('precio', 'desc');
        } else {
            $query->latest();
        }

        $obras = $query->paginate(6)->withQueryString();
        return view('obras.index', compact('obras'));
    }

    public function show($id)
    {
        $obra = Artwork::with('user')->findOrFail($id);
        return view('obras.show', compact('obra'));
    }
}