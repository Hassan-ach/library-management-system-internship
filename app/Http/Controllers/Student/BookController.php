<?php

namespace App\Http\Controllers\Student;

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
}
