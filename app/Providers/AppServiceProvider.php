<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::define('borrow_books', function (User $user, Book $book) {

            return $user->is_active && $book->total_copies > get_borrowed_copies($book);
        });
    }
}
