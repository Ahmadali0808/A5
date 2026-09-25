<?php

use App\Http\Controllers\BandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Login, registreren, uitloggen
Auth::routes();

// Alleen voor ingelogde gebruikers
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::redirect('/dashboard', '/home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/bands/create', [BandController::class, 'create'])->name('bands.create');
    Route::post('/bands', [BandController::class, 'store'])->name('bands.store');
    Route::get('/bands/{band}/edit', [BandController::class, 'edit'])->name('bands.edit');
    Route::put('/bands/{band}', [BandController::class, 'update'])->name('bands.update');
    Route::delete('/bands/{band}', [BandController::class, 'destroy'])->name('bands.destroy');
    Route::post('/bands/{band}/users', [BandController::class, 'addUser'])->name('bands.users.add');
});
// Gast: de EPK pagina van een band
// (onderaan, anders denkt Laravel dat /bands/create een band met id "create" is)
Route::get('/bands/{band}', [BandController::class, 'show'])->name('bands.show');