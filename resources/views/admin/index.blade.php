<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Panel Admin - ArtSpace</title></head>
<body>
    <h1>Panel de Administración</h1>
    <p>
        <a href="{{ route('home') }}">Ver web pública</a> |
        <a href="{{ route('admin.obras.create') }}">+ Nueva Obra</a> |
        <a href="{{ route('admin.mensajes') }}">Mensajes ({{ $mensajesCount }})</a>
    </p>
    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit">Cerrar sesión</button></form>
    <hr>
    @if(session('success')) <p style="color:green">{{ session('success') }}</p> @endif
    <table border="1" cellpadding="8">
        <tr><th>Título</th><th>Técnica</th><th>Precio</th><th>Acciones</th></tr>
        @foreach($obras as $obra)
        <tr>
            <td>{{ $obra->titulo }}</td>
            <td>{{ $obra->tecnica }}</td>
            <td>{{ $obra->precio }} €</td>
            <td>
                <a href="{{ route('admin.obras.edit', $obra) }}">Editar</a>
                <form action="{{ route('admin.obras.destroy', $obra) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Eliminar obra?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>