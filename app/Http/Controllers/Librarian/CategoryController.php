<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $category_service;

    public function __construct(CategoryService $category_service){
        $this->category_service = $category_service;
    }

    public function index( Request $request){
        try {
            $categories = $this->category_service->paginateCategories();
            return view('librarian.categories.index', compact('categories'));
        } catch (\Throwable $e) {

            return view('librarian.categories.index')->with('error', 'Unable to load categories at the moment. Please try again later.');
        }
    }
    
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            if (! $query) {
                $categories = $this->category_service->paginateCategories(10);
            } else {
                $categories = $this->category_service->searchCategories( $query);
            }

            return view('librarian.categories.search', compact('query', 'categories'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('librarian.categories.search')->with('error', 'Unable to load categories at the moment. Please try again later.');

        }
    }

    public function create( Request $request)
    {
        try{
            $validated = $request->validate([
                'label' => 'required | string',
                'description' => 'string'
            ]);
           

            $this->category_service->createCategory( $validated['label'], $validated['description']);

            return view('librarian.categories.create');

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
                'label' => 'string',
                'description' => 'string'
            ]);

            $this->category_service->updateCategory($id, $validated);
            
            
            return view('librarian.categories.edit');

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
            $this->category_service->deleteCategory($id);
            
            return view('librarian.categories.delete');

        }catch (ValidationException $e)
        {
            return view('errors.dataValidation');
        }
        catch(\Throwable $e){
            return view('errors.databaseException');
        }
    }
}