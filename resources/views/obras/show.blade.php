@extends('layouts.app')
@section('title', $obra->titulo . ' - ' . $siteSettings['site_title'])

@section('content')
    <p><a href="{{ route('home') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver al catálogo</a></p>
    <div class="card" style="max-width:820px; margin: 1rem auto;">
        @if($obra->imagen)
            @if($obra->tipo_archivo === 'video')
                <video src="{{ asset('storage/' . $obra->imagen) }}" controls controlsList="nodownload" class="ai-shield" style="width:100%; max-height:480px; background:#000;"></video>
            @else
                <img id="artwork-zoom-trigger" src="{{ asset('storage/' . $obra->imagen) }}" alt="{{ $obra->titulo }}" class="ai-shield" draggable="false" style="width:100%; max-height:460px; object-fit:cover; cursor:zoom-in;" title="Pulsa para ampliar">
            @endif
        @endif
        <div class="card-body">
            <div style="display:flex; gap:0.4rem; margin-bottom:0.5rem;">
                @if($obra->isNew()) <span class="badge badge-new">Nuevo</span> @endif
                @if($obra->destacado) <span class="badge">Destacada</span> @endif
            </div>
            <h1>{{ $obra->titulo }}</h1>
            <p class="price-tag">
                {{ number_format($obra->precio, 2) }} €
                <small style="color:var(--color-muted); font-weight:400; font-size:0.9rem;">
                    (≈ {{ number_format($divisas['USD'], 2) }} USD / {{ number_format($divisas['GBP'], 2) }} GBP)
                </small>
            </p>
            <p><strong>Artista:</strong> {{ $obra->user->name ?? 'Artista independiente' }}</p>
            <p><strong>Categoría / Filtro:</strong> {{ $obra->tecnica }} | <strong>Dimensiones:</strong> {{ $obra->dimensiones ?? 'No especificadas' }}</p>
            <p>{{ $obra->descripcion }}</p>
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-top:1rem;">
                @auth
                    <a href="{{ route('contacto', ['artwork_id' => $obra->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-paper-plane"></i> Consultar sobre esta obra
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-lock"></i> Inicia sesión para contactar con el artista
                    </a>
                @endauth
                <button type="button" class="btn btn-outline" data-fav-id="{{ $obra->id }}">☆ Favorita</button>
            </div>
        </div>
    </div>

    @if($obra->imagen && $obra->tipo_archivo !== 'video')
        <div id="image-lightbox" class="lightbox-modal hidden">
            <img src="{{ asset('storage/' . $obra->imagen) }}" alt="{{ $obra->titulo }}" class="ai-shield">
        </div>
    @endif
@endsection