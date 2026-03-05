<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OllamaController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('interface');
});

// --- Routes de l'Assistant IA ---
Route::get('/assistant', [OllamaController::class, 'index'])->name('ollama.index');
Route::post('/assistant', [OllamaController::class, 'ask'])->name('ollama.ask');

// --- Routes Auth (Breeze) ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';