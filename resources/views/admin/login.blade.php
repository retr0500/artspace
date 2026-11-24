@extends('layouts.app')
@section('title', 'Iniciar Sesión - ArtSpace')

@section('content')
    <div class="card" style="max-width:450px; margin: 2rem auto;">
        <div class="card-body">
            <h1>Iniciar Sesión</h1>
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Entrar</button>
            </form>
            <p style="margin-top:1rem; text-align:center;">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Créala aquí</a>
            </p>
        </div>
    </div>
@endsection