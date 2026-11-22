<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Mensajes - ArtSpace</title></head>
<body>
    <h1>Mensajes de Clientes</h1>
    <a href="{{ route('admin.dashboard') }}">Volver al panel</a>
    <hr>
    @foreach($mensajes as $msg)
        <div>
            <strong>{{ $msg->nombre }}</strong> ({{ $msg->email }}) -
            <em>Obra: {{ $msg->artwork->titulo ?? 'Consulta general' }}</em>
            <p>{{ $msg->mensaje }}</p>
        </div><hr>
    @endforeach
</body>
</html>