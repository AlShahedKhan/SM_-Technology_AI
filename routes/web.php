<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::get('/', function () {
    return view('welcome');
});

// working fine in web 3/23/2025 start
Route::get('/chat', function () {
    return view('chat', ['messages' => session('chat', [])]);
});

Route::post('/chat', [AiController::class, 'sendMessage'])->name('ai.send');
Route::get('/chat/reset', function () {
    session()->forget('chat');
    return redirect('/chat');
});
// working fine in web 3/23/2025 end

