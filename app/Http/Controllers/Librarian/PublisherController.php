<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;
use App\Services\PublisherService;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    private PublisherService $publisher_service;

    public function __construct(PublisherService $publisher_service){
        $this->publisher_service = $publisher_service;
    }

    public function index( Request $request){
        try {
            $publishers = $this->publisher_service->paginatePublishers();
            return view('librarian.publishers.index', compact('publishers'));
        } catch (\Throwable $e) {

            return view('librarian.publishers.index')->with('error', 'Unable to load publishers at the moment. Please try again later.');
        }
    }
    
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            if (! $query) {
                $publishers = $this->publisher_service->paginatePublishers(10);
            } else {
                $publishers = $this->publisher_service->searchPublishers( $query);
            }

            return view('librarian.publishers.search', compact('query', 'publishers'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('librarian.publishers.search')->with('error', 'Unable to load publishers at the moment. Please try again later.');

        }
    }

    public function create( Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => 'required | string'
            ]);
           

            $this->publisher_service->createPublisher( $validated['name']);

            return view('librarian.publishers.create');

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

            $this->publisher_service->updatePublisher($id, $validated);
            
            
            return view('librarian.publishers.edit');

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
            $this->publisher_service->deletePublisher($id);
            
            return view('librarian.publishers.delete');

        }catch (ValidationException $e)
        {
            return view('errors.dataValidation');
        }
        catch(\Throwable $e){
            return view('errors.databaseException');
        }
    }
}