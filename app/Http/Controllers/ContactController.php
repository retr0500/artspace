<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $obraSeleccionada = $request->filled('artwork_id')
            ? Artwork::find($request->artwork_id)
            : null;

        return view('contacto', compact('obraSeleccionada'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->blocked) {
            return back()->withErrors([
                'mensaje' => 'Tu cuenta ha sido bloqueada por el artista y no puedes enviar más mensajes.',
            ]);
        }

        $validated = $request->validate([
            'artwork_id' => 'nullable|exists:artworks,id',
            'mensaje' => 'required|string|min:10|max:2000',
        ]);

        $msg = Message::create([
            'user_id' => $user->id,
            'artwork_id' => $validated['artwork_id'] ?? null,
            'nombre' => $user->name,
            'email' => $user->email,
            'mensaje' => $validated['mensaje'],
        ]);

        // Enviar correo real al artista (admin)
        $adminEmail = config('mail.from.address') ?: User::where('role', 'admin')->value('email');
        if ($adminEmail) {
            try {
                Mail::raw(
                    "Nuevo mensaje en ArtSpace de {$user->name} ({$user->email}):\n\n\"{$msg->mensaje}\"\n\nPuedes responderle desde el Panel de Administración.",
                    function ($mail) use ($adminEmail, $user) {
                        $mail->to($adminEmail)
                             ->replyTo($user->email, $user->name)
                             ->subject("Nueva consulta en ArtSpace de {$user->name}");
                    }
                );
            } catch (\Throwable $e) {
                Log::warning('Error enviando correo al artista: ' . $e->getMessage());
            }
        }

        return redirect()->route('mis-mensajes')
            ->with('success', 'Mensaje enviado correctamente al artista.');
    }

    public function myMessages()
    {
        $mensajes = Message::with('artwork')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mis-mensajes', compact('mensajes'));
    }
}