@extends('layouts.app')
@section('title', 'Panel de Administración - ArtSpace')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <h1>Panel de Administración</h1>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.obras.create') }}" class="btn btn-secondary"><i class="fa-solid fa-plus"></i> Nueva Obra</a>
            <a href="{{ route('admin.mensajes') }}" class="btn btn-outline"><i class="fa-solid fa-envelope"></i> Mensajes ({{ $mensajesCount }})</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Salir</button>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr><th>Título</th><th>Técnica</th><th>Precio</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @foreach($obras as $obra)
                <tr>
                    <td>{{ $obra->titulo }}</td>
                    <td>{{ $obra->tecnica }}</td>
                    <td>{{ number_format($obra->precio, 2) }} €</td>
                    <td style="display:flex; gap:0.5rem;">
                        <a href="{{ route('admin.obras.edit', $obra) }}" class="btn btn-outline">Editar</a>
                        <form action="{{ route('admin.obras.destroy', $obra) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar obra?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection