<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function index(Request $req)
    {
        //
        try {
            $books = Book::latest()->paginate(10);

            return view('books.list', compact('books'));
        } catch (\Exception $e) {

            return view('books.list')->with('error', 'Unable to load books at the moment. Please try again later.');
        }
    }

    public function search(Request $req)
    {
        try {
            $query = $req->input('query') ?? $req->input('q');
            if (! $query) {
                $books = Book::latest()->paginate(1);
            } else {
                $books = Book::where('title', 'like', "%{$query}%")->paginate(10);
            }

            return view('books.search', compact('books', 'query'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('books.search')->with('error', 'Unable to load books at the moment. Please try again later.');

        }
    }
}
