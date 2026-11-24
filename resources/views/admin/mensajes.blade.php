@extends('layouts.app')
@section('title', 'Gestión de Mensajes - ArtSpace')

@section('content')
    <p><a href="{{ route('admin.dashboard') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Volver al panel</a></p>
    <h1>Mensajes de Clientes</h1>

    <form method="GET" action="{{ route('admin.mensajes') }}" class="filter-bar">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre, email o texto..." value="{{ request('buscar') }}">
        <select name="estado" class="form-control">
            <option value="">Todos los mensajes</option>
            <option value="pendientes" {{ request('estado') == 'pendientes' ? 'selected' : '' }}>Pendientes de responder</option>
            <option value="respondidos" {{ request('estado') == 'respondidos' ? 'selected' : '' }}>Ya respondidos</option>
            <option value="bloqueados" {{ request('estado') == 'bloqueados' ? 'selected' : '' }}>De usuarios bloqueados</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filtrar</button>
    </form>

    @forelse($mensajes as $msg)
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="card-body">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                    <div>
                        <h3 style="margin-bottom:0.2rem;">
                            {{ $msg->nombre }} <small style="color:var(--color-muted)">({{ $msg->email }})</small>
                            @if($msg->user && $msg->user->blocked)
                                <span class="badge" style="background:#fee2e2; color:var(--color-error);">Usuario Bloqueado</span>
                            @endif
                        </h3>
                        <span class="badge">Obra: {{ $msg->artwork->titulo ?? 'Consulta general' }}</span>
                    </div>

                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        @if($msg->user && !$msg->user->isAdmin())
                            <form action="{{ route('admin.usuarios.block', $msg->user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline">
                                    <i class="fa-solid fa-ban"></i> {{ $msg->user->blocked ? 'Desbloquear usuario' : 'Bloquear usuario' }}
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.mensajes.destroy', $msg) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este mensaje?')">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <p style="margin-top:0.85rem;">{{ $msg->mensaje }}</p>

                @if($msg->respuesta)
                    <div class="alert alert-success">
                        <strong>Tu respuesta:</strong> {{ $msg->respuesta }}
                    </div>
                @endif

                <form action="{{ route('admin.mensajes.reply', $msg) }}" method="POST" style="margin-top:0.75rem;">
                    @csrf
                    <div class="form-group" style="margin-bottom:0.5rem;">
                        <textarea name="respuesta" rows="2" class="form-control" placeholder="Escribe una respuesta (se enviará por correo y aparecerá en la cuenta del usuario)..." required>{{ $msg->respuesta }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-reply"></i> Responder</button>
                </form>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No hay mensajes que coincidan con el filtro.</div>
    @endforelse
@endsection