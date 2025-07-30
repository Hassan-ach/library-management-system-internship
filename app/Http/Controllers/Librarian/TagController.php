<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{    
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
