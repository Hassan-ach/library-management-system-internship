<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Librarian\RequestController;
use App\Http\Controllers\Librarian\StudentInfoController;
use App\Http\Controllers\Student\BookRequestController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'login-page')->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:web'])->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout']);
    Route::view('/', 'welcome');
    Route::get('/profile', [ProfileController::class, 'index']);

});

Route::middleware(['auth:web', 'role:student'])->group(function () {
    Route::post('request/book/{id}', [BookRequestController::class, 'add'])->name('student.requests.add');
    Route::get('request/{id}', [BookRequestController::class, 'show'])->name('student.requests.show');
    Route::patch('request/{id}', [BookRequestController::class, 'cancel'])->name('student.requests.cancel');
    Route::get('/books/search', [BookController::class, 'search'])->name('student.books.search');
    Route::get('/books', [BookController::class, 'index'])->name('student.books.list');
});

Route::middleware(['auth:web', 'role:librarian'])->group(function () {
    Route::get('/student/{id}', [StudentInfoController::class, 'show'])->name('student.profile.show');
    Route::post('/request/info/{id}', [RequestController::class, 'processe'])->name('request.process');
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.all');
    Route::get('/requests/{id}', [RequestController::class, 'show'])->name('requests.single');
});
