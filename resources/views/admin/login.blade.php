<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login Admin - ArtSpace</title></head>
<body>
    <h1>Acceso Panel ArtSpace</h1>
    @if($errors->any()) <p style="color:red">{{ $errors->first() }}</p> @endif
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <input type="email" name="email" placeholder="Email (admin@artspace.com)" required><br><br>
        <input type="password" name="password" placeholder="Contraseña (admin1234)" required><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>