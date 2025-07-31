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
            return back()->with(['error'=>'Une erreur s\'est produite']);
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

            return view('librarian.books.index')->with('books', $books)
                    ->with('header', 'Résultats trouvés pour « '.$query.' »');

        } catch (\Throwable $th) {
            return back()->with(['error'=>'Une erreur s\'est produite']);
        }
    }

    public function show(Book $book)
    {
        try {
            $data = $this->services->getBookData($book->id);

            return view('librarian.books.show', $data)->with('book', $book);
        } catch (Exception $e) {
            return back()->with(['error'=>'Une erreur s\'est produite']);
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
        } catch (\Throwable $th) {
            return back()->with(['error'=>'Une erreur s\'est produite']);
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
                'isbn' => 'required | string | unique:books', /* the validation process should be stopped if isbn not valide */
                'image_url' => 'nullable | url',
                'publication_date' => 'date',
                'number_of_pages' => 'integer',
                'total_copies' => 'required | integer',
                'description' => 'string',
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

            return redirect('librarian/books')->with(['success' => 'Livre ajouté avec succès !']);

        } catch (ValidationException $e) {
            // return $e;
            $non_valid_field = array_keys($e->errors())[0];
            if( $non_valid_field === 'isbn' && $e->errors()['isbn'][0] === "The isbn has already been taken."){
                return back()->withInput()->with(['error'=>'L\'ISBN que vous avez saisi est déjà attribué à un autre livre']);
            }
            return back()->withInput()->with(['error'=>'les informations que vous avez saisi ne sont pas valides ( '.$non_valid_field.' )']);
        } catch (\Throwable $th) {
            return back()->with(['error'=> 'Une erreur s\'est produite lors de l\'ajout du livre']);
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

            return redirect(route('librarian.books.show',$book))->with(['success' => 'Livre mis à jour avec succès !']);

        } catch (ValidationException $e) {
            // return $e;
            $non_valid_field = array_keys($e->errors())[0];
            return back()->with(['error'=>'les informations que vous avez saisi ne sont pas valides ( '.$non_valid_field.' )']);

        } catch (\Throwable $th) {
            return back()->with(['error'=> 'Une erreur s\'est produite lors de la mise à jour des informations']);
        }
    }

    public function delete(Book $book)
    {
        try {
            $this->services->deleteBook($book->id);

            return redirect('librarian/books')->with(['success' => 'Livre supprimé avec succès']);

        } catch (\Throwable $th) {
            return back()->with(['error'=>'Une erreur s\'est produite lors de la suppression du livre.']);
        }

    }
}
