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

            return view('student.books.index', compact('books'));
        } catch (\Exception $e) {

            return view('student.books.index')->with([
                'error' => 'Impossible de charger les livres pour le moment. Veuillez réessayer plus tard.',
            ]);
        }
    }

    public function show(Request $request, $bookId)
    {
        try {
            $book = Book::with('categories', 'tags', 'authors', 'publishers')->findOrFail($bookId);

            // Return to a view with book data
            return view('student.books.show', compact('book'));
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => 'Une erreur est survenue.',
            ]);

        }
    }

    public function showDetails(Book $book)
    {
        try {
            // Load all necessary relationships for the modal display
            $book->load(['authors', 'categories', 'publishers', 'tags']);

            // Add available_copies as a dynamic attribute for the JSON response
            $book->setAttribute('available_copies', $book->available_copies());

            return response()->json([
                'success' => true,
                'book' => $book,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Impossible de charger les détails du livre.',
                'error' => $e->getMessage(), // Include error message for debugging, remove in production
            ], 500);
        }
    }

    public function search(Request $req)
    {
        try {
            $query = $req->input('query') ?? $req->input('q');
            if (! $query) {
                $books = Book::latest()->paginate(10);
            } else {
                $books = Book::where('title', 'like', "%{$query}%")->paginate(10);
            }

            return view('student.books.search', compact('books', 'query'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('student.books.search')->with([
                'error' => 'Impossible de charger les livres pour le moment. Veuillez réessayer plus tard.',
            ]);

        }
    }
}
