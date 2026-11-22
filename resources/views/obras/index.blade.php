<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>ArtSpace - Galería</title></head>
<body>
    <h1>ArtSpace - Galería de Arte</h1>
    <nav>
        <a href="{{ route('home') }}">Catálogo</a> |
        <a href="{{ route('contacto') }}">Contacto</a> |
        <a href="{{ route('login') }}">Acceso Artistas / Admin</a>
    </nav>
    <hr>

    <form method="GET" action="{{ route('home') }}">
        <input type="text" name="buscar" placeholder="Buscar por título..." value="{{ request('buscar') }}">
        <select name="orden">
            <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <hr>

    @foreach($obras as $obra)
        <article>
            <h3><a href="{{ route('obras.show', $obra->id) }}">{{ $obra->titulo }}</a></h3>
            @if($obra->imagen)
                <img src="{{ asset('storage/' . $obra->imagen) }}" alt="{{ $obra->titulo }}" width="200"><br>
            @endif
            <p>{{ $obra->tecnica }} — <strong>{{ $obra->precio }} €</strong></p>
            <p><small>Artista: {{ $obra->user->name ?? 'Desconocido' }}</small></p>
        </article>
        <hr>
    @endforeach

    {{ $obras->links() }}

    <section>
        <h2>Inspiración Internacional (Art Institute of Chicago)</h2>
        @foreach($inspiracion as $ext)
            <article>
                <h4>{{ $ext['titulo'] }} ({{ $ext['fecha'] }})</h4>
                @if($ext['imagen_url'])
                    <img src="{{ $ext['imagen_url'] }}" alt="{{ $ext['titulo'] }}" width="180"><br>
                @endif
                <p><small>{{ $ext['artista'] }} — {{ $ext['tecnica'] }}</small></p>
            </article>
        @endforeach
    </section>
</body>
</html>