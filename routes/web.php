<?php

use App\Http\Controllers\Admin\AdminUserController;
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


// I didn't add a middleware yet
Route::post('/books/add', [BookController::class, 'add'])->name('librarian.add'); 
Route::post('/books/update', [BookController::class, 'update'])->name('librarian.update'); 

Route::middleware(['auth:web', 'role:librarian'])->group(function () {
    Route::get('/student/{id}', [StudentInfoController::class, 'show'])->name('student.profile.show');
    Route::post('/request/info/{id}', [RequestController::class, 'processe'])->name('request.process');
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.all');
    Route::get('/requests/{id}', [RequestController::class, 'show'])->name('requests.single');
});
// <<<<<<<<<<<<<<<<<<<<<<<<<<< admin Routes

Route::middleware(['auth:web', 'role:student'])
    ->group(function () {
        

    Route::get('/admin_users')->name('admin');

    // gestion des utilisateurs (CRUD)
    Route::prefix('admin')->name('admin.')->group(function() {

        // <<<<<<<<<<<<<<<< users routes

        // show all users
        Route::get('/', [AdminUserController::class, 'users_list'])->name('index');

        // search user
        Route::get('/{user}', [AdminUserController::class, 'search'])->name('search.user');
        

        // <<<<<<<< create & store user
        Route::get('/create', [AdminUserController::class, 'create_user'])->name('create.user');
        Route::post('/', [AdminUserController::class, 'store.user'])->name('store.user');
        // >>>>>>>> create & store user

        // <<<<<<<< update & store user
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit.user');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update.user');
        // >>>>>>>> update & store user

        // <<<<<<<< delete user
        Route::delete('/{user}', [AdminUserController::class, 'delete_user'])->name('delete.user');
        // >>>>>>>> delete user


        // >>>>>>>>>>>>>>>> users routes


        // <<<<<<<<<<<<<<<< settings routes

        // >>>>>>>>>>>>>>>> settings routes
    });
    
});

// >>>>>>>>>>>>>>>>>>>>>>>>>>> admin Routes
