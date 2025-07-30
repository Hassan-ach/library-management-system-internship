<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
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
