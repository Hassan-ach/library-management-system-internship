<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\Services;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    /*
        Any external or reusable logic will be placed in Services .
    */

    private Services $services;

    public function __construct(Services $services)
    {
        $this->services = $services;
    }

    public function index()
    {
        try {
            $books = Book::paginate(20);

            return view('librarian.books.index')->with('books', $books);

        } catch (\Throwable $th) {
            throw $th;
            // return view('librarian.books.index')->with('error', 'Unable to load categories at the moment. Please try again later.');
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            if (! $query) {
                $books = Book::paginate(20);
            } else {
                $books = Book::where('title', 'like', "%{$query}%")->paginate(20);
            }
            if (! count($books)) {
                return view('librarian.books.index')
                    ->with('message', 'Aucun livre est trouvé sous ce titre "'.$query.'"')
                    ->with('books', null);
            }

            return view('librarian.books.index')->with('books', $books);

        } catch (\Throwable $th) {
            throw $th;
            // return view('librarian.books.index')->with('error', 'Unable to load categories at the moment. Please try again later.');

        }
    }

    public function show(Book $book)
    {
        try {
            $data = $this->services->getBookData($book->id);

            return view('librarian.books.show', $data)->with('book', $book);
        } catch (Exception $e) {
            return view('errors.databaseException');
        }
    }

    public function create()
    {
        return view('librarian.books.form');
    }

    public function create_isbn()
    {
        return view('librarian.books.isbn-form');
    }

    public function edit(Book $book)
    {
        try {
            $data = $this->services->getBookData($book->id);
            $action = route('librarian.books.update', $book);
            $method = 'PATCH';

            return view('librarian.books.form', $data)
                ->with('action', $action)
                ->with('method', $method)
                ->with('page_title', 'Modifier les détails du livre')
                ->with('page_header', 'Modifier les détails du livre');
        } catch (Exception $e) {
            return view('errors.databaseException');
        }
    }

    public function store(Request $request)
    {
        /*  Request structure:
            $req = {
                title: ***,
                ...
                description: ***,
                tags : {
                    new : {labels of new tags to be create}
                    old : {ids of old tags}
                },
                categories:{},
                publishers:{},
                authors:{}
            }
        */
        try {
            // decode json items
            $request->merge([
                'tags' => json_decode($request->input('tags'), true),
                'categories' => json_decode($request->input('categories'), true),
                'publishers' => json_decode($request->input('publishers'), true),
                'authors' => json_decode($request->input('authors'), true),
            ]);
            // validate request's values
            $validated = $request->validate([
                'title' => 'required | string',
                'isbn' => 'required | string', /* the validation process should be stopped if isbn not valide */
                'description' => 'string',
                'publication_date' => 'date',
                'number_of_pages' => 'integer',
                'total_copies' => 'required | integer',
                'tags' => 'required',
                'tags.old' => 'array ',
                'tags.new' => 'array',
                'categories' => 'required',
                'categories.old' => 'array',
                'categories.new' => 'array',
                'authors' => 'required',
                'authors.old' => 'array',
                'authors.new' => 'array',
                'publishers' => 'required',
                'publihsers.old' => 'array',
                'publishers.new' => 'array',
            ]);

            // handle the validated data using services class
            $this->services->createBook($validated);

            // return a View showed that the book was created
            // should change to books.show if successe and return back if fail
            return view('librarian.books.create');

        } catch (ValidationException $e) {
            return view('errors.dataValidation');
        } catch (Exception $e) {
            return view('errors.databaseException');
        }
    }

    public function update(Request $request, Book $book)
    {
        try {
            // decode json items
            $request->merge([
                'tags' => json_decode($request->input('tags'), true),
                'categories' => json_decode($request->input('categories'), true),
                'publishers' => json_decode($request->input('publishers'), true),
                'authors' => json_decode($request->input('authors'), true),
            ]);
            // validate
            $validated = $request->validate([
                'title' => 'string',
                'isbn' => 'string',
                'description' => 'string',
                'publication_date' => 'date',
                'number_of_pages' => 'integer',
                'total_copies' => 'integer',
                'tags' => 'required',
                'tags.old' => 'array ',
                'tags.new' => 'array',
                'categories' => 'required',
                'categories.old' => 'array',
                'categories.new' => 'array',
                'authors' => 'required',
                'authors.old' => 'array',
                'authors.new' => 'array',
                'publishers' => 'required',
                'publihsers.old' => 'array',
                'publishers.new' => 'array',
            ]);
            $this->services->updateBook($book->id, $validated);

            return view('librarian.books.edit');

        } catch (ValidationException $e) {
            // return $e;
            return view('errors.dataValidation');
        } catch (Exception $e) {
            return view('errors.databaseException');
        }
    }

    public function delete(Book $book)
    {
        try {
            $this->services->deleteBook($book->id);

            return view('librarian.books.delete'); // temporary
        } catch (ValidationException $e) {
            return view('errors.dataValidation');
        } catch (Exception $e) {
            return view('errors.databaseException');
        }

    }
}
