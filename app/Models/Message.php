<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id', 'artwork_id', 'nombre', 'email',
        'mensaje', 'respuesta', 'respondido_at', 'leido'
    ];

    protected $casts = [
        'leido' => 'boolean',
        'respondido_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }
}