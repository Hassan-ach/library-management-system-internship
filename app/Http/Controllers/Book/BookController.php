<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\Services;
use App;
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
        //
        try {
            //
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

    /* 
        From there, you will find the implementation of the four operations: add(), update(), delete() 
        Any external or reusable logic will be placed in Services .
    */

    public function add(Request $request){
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

        // validate request's values
        
        $validated = $request->validate([
            'title' => 'required | string',
            'isbn' => 'bail | required | unique:books', /* the validation process should be stopped if isbn not valide*/
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
            'publishers.new' => 'array'
        ]);

        // handle the validated data using book services  
        $Service = App::make( Services::class);
        $Service->createBook( $validated);

        // return a View showed that the book was created 
        return view('books.created');
    }

    public function update(Request $request){
        $validated = $request->validate([
            'book_id' => 'int | required',
            'title' => 'required | string',
            'isbn' => 'required',
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
            'publishers.new' => 'array'
        ]);

        $Serice = App::make( Services::class);

        $Serice->updateBook( $validated);

        return view('books.updated');
    }

}
