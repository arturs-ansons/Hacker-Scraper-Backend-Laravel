<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the application'], 200);
});

Route::get('/articles', [ArticlesController::class, 'index'])->name('index');
Route::delete('/articles/{id}', [ArticlesController::class, 'destroy']);
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/login', [RegisterController::class, 'login']);
Route::post('/logout', [RegisterController::class, 'logout']);
