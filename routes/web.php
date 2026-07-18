<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::view('/', 'inicio')->name('inicio');

//GETS 
Route::get('/subir',[PostController::class, 'create'])->name('posts.create');
Route::get('/', [PostController::class, 'index'])->name('home');

//POSTS
Route::post('/subir',[PostController::class, 'store'])->name('posts.store'); //cuando das submit → llama a store() → procesa y guarda
Route::post('/like/{post}', [PostController::class, 'like'])->name('posts.like');