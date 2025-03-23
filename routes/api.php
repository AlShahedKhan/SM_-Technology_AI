<?php

use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/ai', [AiController::class, 'sendMessage']);
