@extends('layouts.app')
@section('title', 'Crear Cuenta - ArtSpace')

@section('content')
    <div class="card" style="max-width:480px; margin: 2rem auto;">
        <div class="card-body">
            <h1>Crear Cuenta en ArtSpace</h1>
            <p><small>Regístrate para guardar tus consultas y contactar directamente con los artistas.</small></p>
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Contraseña (mínimo 6 caracteres)</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Registrarme</button>
            </form>
            <p style="margin-top:1rem; text-align:center;">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
@endsection