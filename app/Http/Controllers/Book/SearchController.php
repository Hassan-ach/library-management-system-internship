<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function index(Request $req)
    {
        //
        $query = $req->input('query') ?? $req->input('q');
        if (! $query) {
            $books = Book::latest()->paginate(1);
        } else {
            $books = Book::where('title', 'like', "%{$query}%")->get();
        }

        return view('books.search', compact('books', 'query'));

    }
}
