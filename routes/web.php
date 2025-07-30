<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Librarian\AuthorController;
use App\Http\Controllers\Librarian\BookController as LibrarianBookController;
use App\Http\Controllers\Librarian\CategoryController;
use App\Http\Controllers\Librarian\GoogleApiService\GoogleApiServiceController;
use App\Http\Controllers\Librarian\LibrarianDashboardController;
use App\Http\Controllers\Librarian\PublisherController;
use App\Http\Controllers\Librarian\RequestController as LibrarianRequestController;
use App\Http\Controllers\Librarian\StudentStatisticsController;
use App\Http\Controllers\Librarian\TagController;
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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
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
        // dashboard
        Route::get('/dashboard', [LibrarianDashboardController::class, 'index'])->name('dashboard');

        Route::get('/requests', [LibrarianRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/{id}', [LibrarianRequestController::class, 'show'])->name('requests.show');
        Route::get('/requests/{reqId}/details', [LibrarianRequestController::class, 'showDetails'])->name('requests.details');
        Route::post('/requests/{id}', [LibrarianRequestController::class, 'process'])->name('requests.process');
        Route::get('/students/{id}', [StudentStatisticsController::class, 'index'])->name('students.statistics');

        // display a list of all books
        Route::get('/books', [LibrarianBookController::class, 'index'])->name('books.index');
        Route::get('/books/search', [LibrarianBookController::class, 'search'])->name('books.search');
        // show form to create book manually
        Route::get('/books/create/isbn', [LibrarianBookController::class, 'create_isbn'])->name('books.create.isbn');

        Route::get('/books/create', [LibrarianBookController::class, 'create'])->name('books.create');
        // add new book by isbn
        Route::get('/books/by-isbn', [GoogleApiServiceController::class, 'getBookInfo'])->name('books.isbn.getInfo');
        // store the data from form(api/manual) to DB
        Route::post('/books', [LibrarianBookController::class, 'store'])->name('books.store');
        // show book info
        Route::get('/books/{book}', [LibrarianBookController::class, 'show'])->name('books.show');
        // show form to edit book infos
        Route::get('/books/{book}/edit', [LibrarianBookController::class, 'edit'])->name('books.edit');
        // update new book infos on database
        Route::patch('/books/{book}', [LibrarianBookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [LibrarianBookController::class, 'delete'])->name('books.delete');

        // These rootes are used to simplify the search process during book's creation
        Route::get('/api/author/search', [AuthorController::class, 'apiSearch'])->name('author.api.search');
        Route::get('/api/publisher/search', [PublisherController::class, 'apiSearch'])->name('publisher.api.search');
        Route::get('/api/category/search', [CategoryController::class, 'apiSearch'])->name('category.api.search');
        Route::get('/api/tag/search', [TagController::class, 'apiSearch'])->name('tag.api.search');

    });
    // admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        //

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests', [AdminDashboardController::class, 'all_requests'])->name('requests.index');
        Route::get('/requests/{id}', [AdminDashboardController::class, 'show'])->name('requests.show');

        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

        // Route::get('/users', [UserController::class, 'create_page'])->name('users.create');
        Route::post('/users', [UserController::class, 'create'])->name('create');
        Route::get('/users/index', [UserController::class, 'index'])->name('users.all');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/{id}', [UserController::class, 'update_page'])->name('users.update');
        Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update.submit');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

        Route::prefix('statistics')->name('statistics.')->group(function () {
            Route::get('/users', [StatisticsController::class, 'users_stat'])->name('users');
            Route::get('/students/search', [StatisticsController::class, 'search'])->name('users.search');
            Route::get('/librarian/search', [StatisticsController::class, 'search_librarian'])->name('librarians.search');
            Route::get('/users/export', [StatisticsController::class, 'exportStudents'])->name('users.export');
            // Route::get('/users/history/{user}', [StatisticsController::class, 'user_history'])->name('users.history');
            Route::get('/student/history/{user}/{status?}/{color?}', [StatisticsController::class, 'user_history'])->name('users.history');
            Route::resource('user_history', UserController::class)->names('user.hitory');

            Route::get('/librarian', [StatisticsController::class, 'librarian_stat'])->name('librarian');
            Route::get('/librarian/history/{user}/{status?}/{color?}', [StatisticsController::class, 'librarian_history'])->name('librarian_history');
            Route::get('/librarian/export', [StatisticsController::class, 'exportLibrarians'])->name('librarian.export');

            Route::get('/books', [StatisticsController::class, 'books_stat'])->name('books');
            Route::get('/books/search', [StatisticsController::class, 'search_book'])->name('books.search');
            Route::get('/books/{book}/history', [StatisticsController::class, 'history'])->name('book.history');
            Route::get('/books/export', [StatisticsController::class, 'exportBooks'])->name('books.export');
        });
    });
});
