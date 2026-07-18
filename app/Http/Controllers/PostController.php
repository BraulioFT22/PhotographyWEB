<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::where('estado', 'publicado')->latest()->get();
        $ip    = request()->ip();

        // IDs de posts que ya le gustaron a este visitante
        $likedIds = Like::where('ip', $ip)->pluck('post_id')->toArray();

        return view('home', compact('posts', 'likedIds'));
    }

    /**
     * Muestra el formulario de subida.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Valida, guarda el archivo en storage/ y crea el registro en la tabla posts.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'imagen'      => 'required|image|max:8192',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2000',
            'estado'      => 'required|in:publicado,borrador',
        ], [
            'imagen.required' => 'Selecciona una imagen para subir.',
            'imagen.image'    => 'El archivo debe ser una imagen (JPG, PNG, etc.).',
            'imagen.max'      => 'La imagen no puede pesar más de 8 MB.',
            'titulo.required' => 'Ponle un título a tu fotografía.',
        ]);

        $ruta = $request->file('imagen')->store('posts', 'public');

        if (!$ruta) {
            return back()->withErrors(['imagen' => 'No se pudo guardar la imagen en el servidor.'])->withInput();
        }

        $post = Post::create([
            'titulo'      => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'imagen'      => $ruta,
            'estado'      => $validated['estado'],
        ]);

        return redirect()
            ->route('posts.create')
            ->with('exito', 'Fotografía publicada correctamente.');
    }
    public function like(Request $request, Post $post)
    {
        $ip = $request->ip();

        // Verifica si esta IP ya dio like a esta foto
        $yaLikeo = Like::where('post_id', $post->id)
                    ->where('ip', $ip)
                    ->exists();

        if ($yaLikeo) {
            // Quita el like (toggle)
            Like::where('post_id', $post->id)->where('ip', $ip)->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            // Da like
            Like::create(['post_id' => $post->id, 'ip' => $ip]);
            $post->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $post->fresh()->likes_count,
        ]);
    }
}