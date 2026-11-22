<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['artwork_id', 'nombre', 'email', 'mensaje', 'leido'];

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }
}