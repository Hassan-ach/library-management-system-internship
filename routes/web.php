<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);
Route::get('/login-page', function () {
    return view('login-page');
});

Route::middleware('auth:scantum')->group(function () {
    Route::get('/lougout', [LogoutController::class, 'logout']);
    Route::get('/', function () {
        return view('welcome');
    });
});
