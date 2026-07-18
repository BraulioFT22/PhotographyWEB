<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
        'imagen',
        'estado',
    ];

    /**
     * Genera el slug automáticamente a partir del título al crear el registro.
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $base = Str::slug($post->titulo);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $post->slug = $slug;
            }
        });
    }

    /**
     * URL pública completa de la imagen (usa el disco "public").
     */
    public function getImagenUrlAttribute(): string
    {
        return asset('storage/' . $this->imagen);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    }