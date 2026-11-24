@extends('layouts.app')
@section('title', 'Mis Mensajes - ArtSpace')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <h1>Mis Mensajes y Respuestas</h1>
        <a href="{{ route('contacto') }}" class="btn btn-secondary"><i class="fa-solid fa-plus"></i> Nueva consulta</a>
    </div>

    @forelse($mensajes as $msg)
        <div class="card" style="margin-bottom:1rem;">
            <div class="card-body">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                    <span class="badge">Obra: {{ $msg->artwork->titulo ?? 'Consulta general' }}</span>
                    <small style="color:var(--color-muted)">Enviado el {{ $msg->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <p style="margin-top:0.75rem;"><strong>Tu consulta:</strong> {{ $msg->mensaje }}</p>

                @if($msg->respuesta)
                    <div class="alert alert-success" style="margin-top:0.75rem; margin-bottom:0;">
                        <strong><i class="fa-solid fa-reply"></i> Respuesta del artista ({{ $msg->respondido_at?->format('d/m/Y H:i') }}):</strong>
                        <p style="margin-top:0.35rem; margin-bottom:0;">{{ $msg->respuesta }}</p>
                    </div>
                @else
                    <p style="color:var(--color-muted); margin-bottom:0;"><small><i class="fa-regular fa-clock"></i> Pendiente de respuesta por el artista.</small></p>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">Todavía no has enviado ningún mensaje.</div>
    @endforelse
@endsection