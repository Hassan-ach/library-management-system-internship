<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::get('/not-found', function () {
    return view('errors.404');
})->name('notfound');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/login-page', function () {
    return view('login-page');
})->name('login');

Route::middleware(['auth:web'])->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout']);
    Route::get('/', function () {
        return view('welcome');
    });
});
