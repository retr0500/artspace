@extends('layouts.app')
@section('title', 'Nueva Obra - ArtSpace')

@section('content')
    <p><a href="{{ route('home') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver</a></p>
    <div class="card" style="max-width:650px; margin: 1rem auto;">
        <div class="card-body">
            <h1>Subir Nueva Obra o Vídeo</h1>
            <form action="{{ route('admin.obras.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group"><label>Título</label><input type="text" name="titulo" class="form-control" required></div>
                <div class="form-group"><label>Descripción</label><textarea name="descripcion" rows="4" class="form-control" required></textarea></div>
                <div class="form-group">
                    <label>Categoría / Filtro</label>
                    <select name="tecnica" class="form-control" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Dimensiones / Duración</label><input type="text" name="dimensiones" class="form-control" placeholder="Ej. 100x80 cm o 01:30 min"></div>
                <div class="form-group"><label>Precio (€)</label><input type="number" step="0.01" name="precio" class="form-control" required></div>
                <div class="form-group"><label>Archivo (Imagen JPG/PNG/WEBP o Vídeo MP4/WEBM)</label><input type="file" name="archivo" class="form-control" accept="image/*,video/mp4,video/webm,video/quicktime"></div>
                <div class="form-group">
                    <label>Etiqueta "Nuevo" (duración en horas reales; 0 = desactivada)</label>
                    <input type="number" name="nuevo_horas" class="form-control" value="24" min="0" max="720">
                </div>
                <div class="form-group"><label><input type="checkbox" name="destacado"> Obra destacada</label></div>
                <button type="submit" class="btn btn-primary">Publicar Obra</button>
            </form>
        </div>
    </div>
@endsection