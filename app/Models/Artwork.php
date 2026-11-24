<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    protected $fillable = [
        'user_id', 'titulo', 'descripcion', 'tecnica',
        'dimensiones', 'precio', 'imagen', 'tipo_archivo',
        'destacado', 'nuevo_hasta'
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'nuevo_hasta' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isNew(): bool
    {
        return $this->nuevo_hasta !== null && now()->lt($this->nuevo_hasta);
    }
}