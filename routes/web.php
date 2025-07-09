<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'login-page')->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:web'])->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout']);
    Route::view('/', 'welcome');
    Route::get('/profile', [ProfileController::class, 'index']);

});

Route::middleware(['auth:web', 'role:student'])->group(function () {
    //
});
