<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/chatroom1', function () {
    return view('chatroom.chatroom1');
})->middleware('auth')->name('chatroom1');

Route::get('/chatroom2', function () {
    return view('chatroom.chatroom2');
})->middleware('auth')->name('chatroom2');

Route::get('/chatroom3', function () {
    return view('chatroom.chatroom3');
})->middleware('auth')->name('chatroom3');



require __DIR__.'/auth.php';
