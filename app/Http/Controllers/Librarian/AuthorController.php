<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Services\AuthorService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    private AuthorService $author_service;

    public function __construct(AuthorService $author_service){
        $this->author_service = $author_service;
    }

    public function index( Request $request){
        try {
            $authors = $this->author_service->paginateAuthors();
            return view('librarian.authors.index', compact('authors'));
        } catch (\Throwable $e) {

            return view('librarian.authors.index')->with('error', 'Unable to load authors at the moment. Please try again later.');
        }
    }
    
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            if (! $query) {
                $authors = $this->author_service->paginateAuthors(10);
            } else {
                $authors = $this->author_service->searchAuthors( $query);
            }

            return view('librarian.authors.search', compact('query', 'authors'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('librarian.authors.search')->with('error', 'Unable to load authors at the moment. Please try again later.');

        }
    }

    public function create( Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required | string'
            ]);
           

            $this->author_service->createAuthor( $validated['name']);

            return view('librarian.authors.create');

        }catch(ValidationException $e){
            return view('errors.dataValidation');
        }
        catch(\Throwable $e){
            return view('errors.databaseException');
        }
    }

    public function update(Request $request, $id)
    {   
        try{
            $validated = $request->validate([
                'name' => 'required | string'
            ]);

            $this->author_service->updateAuthor($id, $validated['name']);
            
            
            return view('librarian.authors.edit');

        }catch (ValidationException $e)
        {
            return view('errors.dataValidation');
        }
        catch(\Throwable $e){
            return view('errors.databaseException');
        }
    }

    public function delete(Request $request, int $id)
    {   
        try{
            $this->author_service->deleteAuthor($id);
            
            return view('librarian.authors.delete');

        }catch (ValidationException $e)
        {
            return view('errors.dataValidation');
        }
        catch(\Throwable $e){
            return view('errors.databaseException');
        }
    }

    public function apiSearch(Request $request)
    {
        try {
            $query = $request->input('q');
            $authors = Author::where('name', 'LIKE', "%{$query}%")
                   ->limit(7)
                   ->get();

            return response()->json(
                $authors->map( function($author){
                    return ['id'=>$author->id, 'name'=>$author->name];
                })
            );
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([]);
        }
    }
}
