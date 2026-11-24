@extends('layouts.app')
@section('title', $siteSettings['site_title'] . ' - Catálogo')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>{{ $siteSettings['hero_title'] }}</h1>
            <p>{{ $siteSettings['hero_subtitle'] }}</p>
        </div>
        @if(Auth::check() && Auth::user()->isAdmin() && session('edit_mode'))
            <a href="{{ route('admin.obras.create') }}" class="btn btn-secondary">
                <i class="fa-solid fa-plus"></i> Subir Nueva Obra / Vídeo
            </a>
        @endif
    </div>

    @if(Auth::check() && Auth::user()->isAdmin() && session('edit_mode'))
        <div class="card" style="padding:1rem; margin-bottom:1.25rem; background:#f8fafc;">
            <strong><i class="fa-solid fa-tags"></i> Gestionar Filtros / Categorías:</strong>
            <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin:0.65rem 0;">
                @foreach($categorias as $cat)
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline-flex; align-items:center; gap:0.35rem; background:#fff; border:1px solid var(--color-border); padding:0.25rem 0.6rem; border-radius:999px;">
                        @csrf @method('DELETE')
                        <span>{{ $cat->nombre }}</span>
                        <button type="submit" style="border:none; background:none; color:var(--color-error); cursor:pointer; font-weight:700;" title="Borrar filtro">&times;</button>
                    </form>
                @endforeach
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" style="display:flex; gap:0.5rem; max-width:360px;">
                @csrf
                <input type="text" name="nombre" class="form-control" placeholder="Nuevo filtro (ej. Escultura)..." required>
                <button type="submit" class="btn btn-primary">Añadir</button>
            </form>
        </div>
    @endif

    <form method="GET" action="{{ route('home') }}" class="filter-bar">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar obra por título..." value="{{ request('buscar') }}">
        <select name="tecnica" class="form-control">
            <option value="">Todos los filtros</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->nombre }}" {{ request('tecnica') == $cat->nombre ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
        </select>
        <select name="orden" class="form-control">
            <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Filtrar</button>
    </form>

    <div class="gallery-grid">
        @forelse($obras as $obra)
            <article class="card card-clickable" data-href="{{ route('obras.show', $obra->id) }}">
                @if($obra->imagen)
                    @if($obra->tipo_archivo === 'video')
                        <video src="{{ asset('storage/' . $obra->imagen) }}" class="card-img ai-shield" muted loop playsinline onmouseover="this.play()" onmouseout="this.pause()"></video>
                    @else
                        <img src="{{ asset('storage/' . $obra->imagen) }}" alt="{{ $obra->titulo }}" class="card-img ai-shield" draggable="false">
                    @endif
                @else
                    <div class="card-placeholder"><i class="fa-regular fa-image fa-2x"></i></div>
                @endif
                <div class="card-body">
                    <div>
                        <div style="display:flex; gap:0.35rem; flex-wrap:wrap;">
                            @if($obra->isNew()) <span class="badge badge-new">Nuevo</span> @endif
                            @if($obra->destacado) <span class="badge">Destacada</span> @endif
                            @if($obra->tipo_archivo === 'video') <span class="badge" style="background:#e0f2fe; color:#0369a1;">Vídeo</span> @endif
                        </div>
                        <h3 class="card-title" style="margin-top:0.45rem">
                            <a href="{{ route('obras.show', $obra->id) }}">{{ $obra->titulo }}</a>
                        </h3>
                        <p>{{ $obra->tecnica }} &bull; <small>{{ $obra->user->name ?? 'Artista' }}</small></p>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.75rem; gap:0.5rem; flex-wrap:wrap;">
                        <span class="price-tag">{{ number_format($obra->precio, 2) }} €</span>
                        <button type="button" class="btn btn-outline" data-fav-id="{{ $obra->id }}">☆ Favorita</button>
                    </div>

                    @if(Auth::check() && Auth::user()->isAdmin() && session('edit_mode'))
                        <div style="display:flex; gap:0.5rem; margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid var(--color-border);">
                            <a href="{{ route('admin.obras.edit', $obra) }}" class="btn btn-outline" style="flex:1;"><i class="fa-solid fa-pen"></i> Editar</a>
                            <form action="{{ route('admin.obras.destroy', $obra) }}" method="POST" style="flex:1;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="width:100%;" onclick="return confirm('¿Eliminar esta obra?')"><i class="fa-solid fa-trash"></i> Borrar</button>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="alert alert-info">No se encontraron obras con los filtros seleccionados.</div>
        @endforelse
    </div>

    {{ $obras->links() }}
@endsection