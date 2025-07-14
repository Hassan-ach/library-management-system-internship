<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookSearchController extends Controller
{
    //
    public function search(Request $req)
    {
        try {
            $query = $req->input('query') ?? $req->input('q');
            if (! $query) {
                $books = Book::latest()->paginate(1);
            } else {
                $books = Book::where('title', 'like', "%{$query}%")->paginate(10);
            }

            return view('student.books.search', compact('books', 'query'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('student.books.search')->with('error', 'Unable to load books at the moment. Please try again later.');

        }
    }
}
