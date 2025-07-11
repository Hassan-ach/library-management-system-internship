<?php

namespace App\Providers;

use App\Enums\RequestStatus;
use App\Models\Book;
use App\Models\BookRequest;
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

        Gate::define('cancel_req', function (User $user, BookRequest $req) {
            return $user->is_active && $user->id == $req->user_id && get_latest_info($req->id)->status == RequestStatus::PENDING;
        });

        Gate::define('show_req', function (User $user, BookRequest $req) {
            return $user->is_active && $user->id == $req->user_id;
        });
    }
}
