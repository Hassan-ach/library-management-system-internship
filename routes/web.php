<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Librarian\BookController as LibrarianBookController;
use App\Http\Controllers\Librarian\RequestController as LibrarianRequestController;
use App\Http\Controllers\Librarian\StudentStatisticsController;
use App\Http\Controllers\Student\BookController as StudentBookController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\RequestController as StudentRequestController;
use App\Http\Controllers\Student\StudentDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role == UserRole::ADMIN) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role == UserRole::LIBRARIAN) {
            return redirect()->route('librarian.dashboard');
        } elseif (Auth::user()->role == UserRole::STUDENT) {
            return redirect()->route('student.dashboard');
        }
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::view('/login', 'auth.login')->name('login');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/books', [StudentBookController::class, 'index'])->name('books.index');
        Route::get('/books/search', [StudentBookController::class, 'search'])->name('books.search');
        Route::get('/requests/{id}', [StudentRequestController::class, 'show'])->name('requests.show');
        Route::post('/reqests/book/{id}', [StudentRequestController::class, 'create'])->name('requests.create');
        Route::get('/requests/cancel/{id}', [StudentRequestController::class, 'cancel'])->name('requests.cancel');
    });
    // librarian
    Route::prefix('librarian')->name('librarian.')->middleware('role:librarian')->group(function () {
        //
        Route::get('/requests', [LibrarianRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/{id}', [LibrarianRequestController::class, 'show'])->name('requests.show');
        Route::post('/requests/{id}', [LibrarianRequestController::class, 'process'])->name('requests.process');
        Route::get('/students/{id}', [StudentStatisticsController::class, 'index'])->name('students.statistics');
        Route::post('/books', [LibrarianBookController::class, 'create'])->name('books.create');
        Route::patch('/books/{id}', [LibrarianBookController::class, 'update'])->name('books.update');
        Route::delete('/books/{id}', [LibrarianBookController::class, 'delete'])->name('books.delete');
    });
    // admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        //
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.get');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::post('/users', [UserController::class, 'create'])->name('users.create');
        Route::get('/users', [UserController::class, 'index'])->name('users.all');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');
    });
});
