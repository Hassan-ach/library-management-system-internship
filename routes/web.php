<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'login-page')->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:web'])->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout']);
    Route::view('/', 'welcome');

});

Route::middleware(['role:student'])->group(function () {
    //
    Route::get('/profile/{id}', [ProfileController::class, 'index']);
});
