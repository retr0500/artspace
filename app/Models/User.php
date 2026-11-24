<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'blocked'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}