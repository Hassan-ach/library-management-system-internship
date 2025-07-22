<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    private TagService $tag_service;

    public function __construct(TagService $tag_service){
        $this->tag_service = $tag_service;
    }

    public function index( Request $request){
        try {
            $tags = $this->tag_service->paginateTags();
            return view('librarian.tags.index', compact('tags'));
        } catch (\Throwable $e) {

            return view('librarian.tags.index')->with('error', 'Unable to load tags at the moment. Please try again later.');
        }
    }
    
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            if (! $query) {
                $tags = $this->tag_service->paginateTags(10);
            } else {
                $tags = $this->tag_service->searchTags( $query);
            }

            return view('librarian.tags.search', compact('query', 'tags'));
        } catch (\Throwable $th) {
            // throw $th;
            return view('librarian.tags.search')->with('error', 'Unable to load tags at the moment. Please try again later.');

        }
    }

    public function create( Request $request)
    {
        try{
            $validated = $request->validate([
                'label' => 'required | string'
            ]);
           

            $this->tag_service->createTag( $validated['label']);

            return view('librarian.tags.create');

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
                'label' => 'required | string'
            ]);

            $this->tag_service->updateTag($id, $validated);
            
            
            return view('librarian.tags.edit');

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
            $this->tag_service->deleteTag($id);
            
            return view('librarian.tags.delete');

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
            $tags = Tag::where('label', 'LIKE', "%{$query}%")
                   ->limit(10)
                   ->get();

            return response()->json(
                $tags->map( function($tag){
                    return ['id'=>$tag->id, 'label'=>$tag->label];
                })
            );
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([]);
        }
    }
}
