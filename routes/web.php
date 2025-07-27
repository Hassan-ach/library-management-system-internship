<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Librarian\AuthorController;
use App\Http\Controllers\Librarian\CategoryController;
use App\Http\Controllers\Librarian\PublisherController;
use App\Http\Controllers\Librarian\TagController;

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Librarian\BookController as LibrarianBookController;
use App\Http\Controllers\Librarian\GoogleApiService\GoogleApiServiceController;
use App\Http\Controllers\Librarian\RequestController as LibrarianRequestController;
use App\Http\Controllers\Librarian\StudentStatisticsController;
use App\Http\Controllers\Student\BookController as StudentBookController;
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
    Route::get('/forgot-password', [PasswordController::class, 'forget_form'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'reset_form'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

});

Route::middleware('auth:web')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/books', [StudentBookController::class, 'index'])->name('books.index');
        Route::get('/books/search', [StudentBookController::class, 'search'])->name('books.search');
        Route::get('/books/{id}', [StudentBookController::class, 'show'])->name('books.show');
        Route::get('/books/{book}/details', [StudentBookController::class, 'showDetails'])->name('books.details');

        Route::get('/requests', [StudentRequestController::class, 'index'])->name('requests.index');
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
        // Book Routes: 
        Route::post('/books', [LibrarianBookController::class, 'create'])->name('books.create');
        Route::get('/books/add', [LibrarianBookController::class, 'isbnForm'])->name('books.isbnForm');
        Route::post('/books/add', [GoogleApiServiceController::class, 'getBookInfo'])->name('books.isbn.getInfo');
        Route::patch('/books/{id}', [LibrarianBookController::class, 'update'])->name('books.update');
        Route::delete('/books/{id}', [LibrarianBookController::class, 'delete'])->name('books.delete');
        // Author Routes:
        Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
        Route::get('/authors/search', [AuthorController::class, 'search'])->name('authors.search');
        Route::post('/authors', [AuthorController::class, 'create'])->name( 'authors.create');
        Route::patch('/authors/{id}', [AuthorController::class, 'update'])->name( 'authors.update');
        Route::delete('/authors/{id}', action: [AuthorController::class, 'delete'])->name( 'authors.delete');
        // Category Routes:
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category/search', [CategoryController::class, 'search'])->name('category.search');
        Route::post('/category', [CategoryController::class, 'create'])->name( 'category.create');
        Route::patch('/category/{id}', [CategoryController::class, 'update'])->name( 'category.update');
        Route::delete('/category/{id}',  [CategoryController::class, 'delete'])->name( 'category.delete');
        // Publisher Routes:
        Route::get('/publishers', [PublisherController::class, 'index'])->name('publishers.index');
        Route::get('/publishers/search', [PublisherController::class, 'search'])->name('publishers.search');
        Route::post('/publishers', [PublisherController::class, 'create'])->name( 'publishers.create');
        Route::patch('/publishers/{id}', [PublisherController::class, 'update'])->name( 'publishers.update');
        Route::delete('/publishers/{id}',  [PublisherController::class, 'delete'])->name( 'publishers.delete');
        //Tag Routes:
        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
        Route::post('/tags', [TagController::class, 'create'])->name( 'tags.create');
        Route::patch('/tags/{id}', [TagController::class, 'update'])->name( 'tags.update');
        Route::delete('/tags/{id}',  [TagController::class, 'delete'])->name( 'tags.delete');
    });
    // admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        //

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminDashboardController::class,'profile'])->name('profile');

        
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.get');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Route::get('/users', [UserController::class, 'create_page'])->name('users.create');
        Route::post('/users', [UserController::class, 'create'])->name('create');
        Route::get('/users/index', [UserController::class, 'index'])->name('users.all');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/{id}', [UserController::class, 'update_page'])->name('users.update');
        Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update.submit');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');
        
        
        
        Route::prefix('statistics')->name('statistics.')->group(function () {
            Route::get('/users', [StatisticsController::class,'users_stat'])->name('users');
            Route::get('/students/search', [StatisticsController::class, 'search'])->name('users.search');
            Route::get('/librarian/search', [StatisticsController::class, 'search_librarian'])->name('librarians.search');
            Route::get('/users/export', [StatisticsController::class,'exportUsers'])->name('users.export');
            // Route::get('/users/history/{user}', [StatisticsController::class, 'user_history'])->name('users.history');
            Route::get('/student/history/{user}/{status?}/{color?}', [StatisticsController::class, 'user_history'])->name('users.history');
            Route::get('/librarian/history/{user}/{status?}/{color?}', [StatisticsController::class, 'librarian_history'])->name('librarian.history');
            Route::resource('user_history', UserController::class)->names('user.hitory');


            Route::get('/librarian', [StatisticsController::class,'librarian_stat'])->name('librarian');
            Route::get('/librarian/export', [StatisticsController::class,'exportlibrarian'])->name('librarian.export');

            Route::get('/books', [StatisticsController::class,'books_stat'])->name('books');
            Route::get('/books/export', [StatisticsController::class,'exportBooks'])->name('books.export');
        });
    });
});
