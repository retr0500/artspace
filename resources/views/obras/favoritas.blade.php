@extends('layouts.app')
@section('title', 'Mis Obras Favoritas - ArtSpace')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h1>Mis Obras Favoritas</h1>
            <p>Obras guardadas en tu navegador para revisarlas cuando quieras.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver al catálogo</a>
    </div>

    <div id="favorites-list-container"></div>
@endsection