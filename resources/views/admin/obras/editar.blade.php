@extends('layouts.app')
@section('title', 'Editar Obra - ArtSpace')

@section('content')
    <p><a href="{{ route('home') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver</a></p>
    <div class="card" style="max-width:650px; margin: 1rem auto;">
        <div class="card-body">
            <h1>Editar: {{ $artwork->titulo }}</h1>
            <form action="{{ route('admin.obras.update', $artwork) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group"><label>Título</label><input type="text" name="titulo" class="form-control" value="{{ $artwork->titulo }}" required></div>
                <div class="form-group"><label>Descripción</label><textarea name="descripcion" rows="4" class="form-control" required>{{ $artwork->descripcion }}</textarea></div>
                <div class="form-group">
                    <label>Categoría / Filtro</label>
                    <select name="tecnica" class="form-control" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->nombre }}" {{ $artwork->tecnica === $cat->nombre ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Dimensiones / Duración</label><input type="text" name="dimensiones" class="form-control" value="{{ $artwork->dimensiones }}"></div>
                <div class="form-group"><label>Precio (€)</label><input type="number" step="0.01" name="precio" class="form-control" value="{{ $artwork->precio }}" required></div>
                <div class="form-group"><label>Cambiar archivo (Imagen o Vídeo opcional)</label><input type="file" name="archivo" class="form-control" accept="image/*,video/mp4,video/webm,video/quicktime"></div>
                <div class="form-group">
                    <label>Etiqueta "Nuevo" (Estado actual: {{ $artwork->isNew() ? 'Activa hasta ' . $artwork->nuevo_hasta->format('d/m H:i') : 'Inactiva' }})</label>
                    <select name="accion_nuevo" class="form-control">
                        <option value="mantener">Mantener estado actual</option>
                        <option value="24h">Activar / Reiniciar 24 horas</option>
                        <option value="personalizar">Ampliar / Disminuir horas personalizadas</option>
                        <option value="desactivar">Desactivar etiqueta "Nuevo"</option>
                    </select>
                    <input type="number" name="horas_personalizadas" class="form-control" style="margin-top:0.4rem;" placeholder="Horas desde ahora (ej. 6, 48, 72...)" min="1" max="720">
                </div>
                <div class="form-group"><label><input type="checkbox" name="destacado" {{ $artwork->destacado ? 'checked' : '' }}> Obra destacada</label></div>
                <button type="submit" class="btn btn-primary">Actualizar Obra</button>
            </form>
        </div>
    </div>
@endsection