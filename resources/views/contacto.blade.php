@extends('layouts.app')
@section('title', 'Contactar con el Artista - ArtSpace')

@section('content')
    <div class="card" style="max-width:600px; margin: 1rem auto;">
        <div class="card-body">
            <h1>Enviar Consulta al Artista</h1>
            <p>Conectado como <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</p>

            @if($obraSeleccionada)
                <div class="alert alert-info">Consulta sobre la obra: <strong>{{ $obraSeleccionada->titulo }}</strong></div>
            @endif

            @if(Auth::user()->blocked)
                <div class="alert alert-error">Tu cuenta ha sido bloqueada por el administrador y no puedes enviar más mensajes.</div>
            @else
                <form action="{{ route('contacto.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="artwork_id" value="{{ request('artwork_id') }}">
                    <div class="form-group">
                        <label>Tu mensaje o propuesta</label>
                        <textarea name="mensaje" rows="5" class="form-control" placeholder="Escribe tu consulta para el artista..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Enviar mensaje</button>
                </form>
            @endif
        </div>
    </div>
@endsection