<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Message;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ArtworkAdminController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_if(!Auth::check() || !Auth::user()->isAdmin(), 403, 'Acceso exclusivo para el artista.');
    }

    public function index()
    {
        $this->ensureAdmin();
        $obras = Artwork::where('user_id', Auth::id())->latest()->get();
        $mensajesCount = Message::whereNull('respuesta')->count();
        $categorias = Category::orderBy('id')->get();
        return view('admin.index', compact('obras', 'mensajesCount', 'categorias'));
    }

    public function toggleEditMode()
    {
        $this->ensureAdmin();
        $current = session('edit_mode', false);
        session(['edit_mode' => !$current]);
        return back()->with('success', !$current ? 'Modo Edición ACTIVADO.' : 'Modo Edición DESACTIVADO.');
    }

    public function updateSettings(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'site_title' => 'required|string|max:60',
            'hero_title' => 'required|string|max:120',
            'hero_subtitle' => 'required|string|max:255',
            'footer_text' => 'required|string|max:255',
            'color_primary' => 'required|string|max:20',
            'color_secondary' => 'required|string|max:20',
            'color_bg' => 'required|string|max:20',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            SiteSetting::updateOrCreate(['key' => 'logo_path'], ['value' => $path]);
        }

        SiteSetting::updateOrCreate(
            ['key' => 'ai_protection'],
            ['value' => $request->has('ai_protection') ? '1' : '0']
        );

        foreach (['site_title', 'hero_title', 'hero_subtitle', 'footer_text', 'color_primary', 'color_secondary', 'color_bg'] as $key) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $data[$key]]);
        }

        return back()->with('success', 'Personalización y paleta de colores guardadas.');
    }

    public function storeCategory(Request $request)
    {
        $this->ensureAdmin();
        $request->validate(['nombre' => 'required|string|max:50|unique:categories,nombre']);
        Category::create(['nombre' => trim($request->nombre)]);
        return back()->with('success', 'Filtro/categoría añadido.');
    }

    public function destroyCategory(Category $category)
    {
        $this->ensureAdmin();
        $category->delete();
        return back()->with('success', 'Filtro/categoría eliminado.');
    }

    public function create()
    {
        $this->ensureAdmin();
        $categorias = Category::orderBy('id')->get();
        return view('admin.obras.crear', compact('categorias'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'tecnica' => 'required|string|max:100',
            'dimensiones' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov|max:20480',
            'nuevo_horas' => 'nullable|integer|min:0|max:720',
        ]);

        $tipoArchivo = 'imagen';
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $ext = strtolower($file->getClientOriginalExtension());
            $tipoArchivo = in_array($ext, ['mp4', 'webm', 'mov']) ? 'video' : 'imagen';
            $data['imagen'] = $file->store('obras', 'public');
        }

        $horasNuevo = (int) ($request->input('nuevo_horas', 24));
        $data['nuevo_hasta'] = $horasNuevo > 0 ? now()->addHours($horasNuevo) : null;
        $data['tipo_archivo'] = $tipoArchivo;
        $data['user_id'] = Auth::id();
        $data['destacado'] = $request->has('destacado');

        Artwork::create($data);
        return redirect()->route('home')->with('success', 'Obra publicada correctamente.');
    }

    public function edit(Artwork $artwork)
    {
        $this->ensureAdmin();
        abort_if($artwork->user_id !== Auth::id(), 403);
        $categorias = Category::orderBy('id')->get();
        return view('admin.obras.editar', compact('artwork', 'categorias'));
    }

    public function update(Request $request, Artwork $artwork)
    {
        $this->ensureAdmin();
        abort_if($artwork->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'tecnica' => 'required|string|max:100',
            'dimensiones' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov|max:20480',
            'accion_nuevo' => 'nullable|string',
            'horas_personalizadas' => 'nullable|integer|min:1|max:720',
        ]);

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $ext = strtolower($file->getClientOriginalExtension());
            $data['tipo_archivo'] = in_array($ext, ['mp4', 'webm', 'mov']) ? 'video' : 'imagen';
            $data['imagen'] = $file->store('obras', 'public');
        }

        // Control real del tiempo de la etiqueta "Nuevo"
        $accionNuevo = $request->input('accion_nuevo', 'mantener');
        if ($accionNuevo === 'desactivar') {
            $data['nuevo_hasta'] = null;
        } elseif ($accionNuevo === '24h') {
            $data['nuevo_hasta'] = now()->addHours(24);
        } elseif ($accionNuevo === 'personalizar' && $request->filled('horas_personalizadas')) {
            $data['nuevo_hasta'] = now()->addHours((int) $request->horas_personalizadas);
        }

        $data['destacado'] = $request->has('destacado');
        $artwork->update($data);

        return redirect()->route('home')->with('success', 'Obra actualizada.');
    }

    public function destroy(Artwork $artwork)
    {
        $this->ensureAdmin();
        abort_if($artwork->user_id !== Auth::id(), 403);
        $artwork->delete();
        return back()->with('success', 'Obra eliminada.');
    }

    public function mensajes(Request $request)
    {
        $this->ensureAdmin();
        $query = Message::with(['artwork', 'user']);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('mensaje', 'like', "%{$buscar}%");
            });
        }

        if ($request->estado === 'pendientes') {
            $query->whereNull('respuesta');
        } elseif ($request->estado === 'respondidos') {
            $query->whereNotNull('respuesta');
        } elseif ($request->estado === 'bloqueados') {
            $query->whereHas('user', fn ($q) => $q->where('blocked', true));
        }

        $mensajes = $query->latest()->get();
        return view('admin.mensajes', compact('mensajes'));
    }

    public function replyMessage(Request $request, Message $message)
    {
        $this->ensureAdmin();
        $request->validate(['respuesta' => 'required|string|min:5|max:2000']);

        $message->update([
            'respuesta' => $request->respuesta,
            'respondido_at' => now(),
            'leido' => true,
        ]);

        try {
            Mail::raw(
                "Hola {$message->nombre},\n\nEl artista de ArtSpace ha respondido a tu consulta:\n\n\"{$request->respuesta}\"\n\nTambién puedes verla en 'Mis Mensajes' dentro de la web.",
                function ($mail) use ($message) {
                    $mail->to($message->email)
                         ->subject('Respuesta del artista - ArtSpace');
                }
            );
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar email SMTP: ' . $e->getMessage());
        }

        return back()->with('success', 'Respuesta enviada por correo y publicada en el panel del usuario.');
    }

    public function toggleBlockUser(User $user)
    {
        $this->ensureAdmin();
        abort_if($user->isAdmin(), 403);
        $user->update(['blocked' => !$user->blocked]);
        return back()->with('success', $user->blocked ? "Usuario {$user->name} bloqueado." : "Usuario {$user->name} desbloqueado.");
    }

    public function destroyMessage(Message $message)
    {
        $this->ensureAdmin();
        $message->delete();
        return back()->with('success', 'Mensaje eliminado.');
    }
}