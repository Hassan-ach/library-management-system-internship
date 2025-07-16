<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\Services;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;


class BookController extends Controller
{
    /*
        Any external or reusable logic will be placed in Services .
    */

    public function create(Request $request)
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

        // validate request's values

        $validated = $request->validate([
            'title' => 'required | string',
            'isbn' => 'bail | required | unique:books', /* the validation process should be stopped if isbn not valide */
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

        // handle the validated data using book services
        $Service = App::make(Services::class);
        $Service->createBook($validated);

        // return a View showed that the book was created
        return view('librarian.books.create');
    }

    public function update(Request $request, $id)
    {   
    try{
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
            'publishers.new' => 'array'
        ]);
        $Serice = App::make(Services::class);
        
        $Serice->updateBook($id, $validated);
        
        return view('librarian.books.edit');

    }catch (ValidationException $e)
    {
        return view('errors.dataValidation');
    }
    catch(Exception $e){
        return view('errors.databaseException');
    }
    }

    public function delete(Request $request, $bookId)
    {
        $Service = App::make(Services::class);

        $Service->deleteBook($bookId);
    }
}
