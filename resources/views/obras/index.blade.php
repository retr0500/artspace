<!DOCTYPE html>
<html>
<head>
    <title>ArtSpace - MVP</title>
</head>
<body>
    <h1>Bienvenido a ArtSpace</h1>
    
    <h3>Añadir nueva obra (CRUD Básico)</h3>
    <form action="/guardar-obra" method="POST">
        @csrf
        <label>Título:</label><br>
        <input type="text" name="titulo" required><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion" required></textarea><br><br>

        <label>Precio (€):</label><br>
        <input type="number" step="0.01" name="precio" required><br><br>

        <button type="submit">Guardar Obra</button>
    </form>

    <hr>

    <h3>Listado de Obras en la Base de Datos</h3>
    <ul>
        @foreach($obras as $obra)
            <li>
                <strong>{{ $obra->titulo }}</strong> - {{ $obra->precio }}€ <br>
                <small>{{ $obra->descripcion }}</small>
            </li>
        @endforeach
    </ul>
</body>
</html>