<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function apiSearch(Request $request)
    {
        try {
            $query = $request->input('q');
            $publishers = Publisher::where('name', 'LIKE', "%{$query}%")
                   ->limit(7)
                   ->get();

            return response()->json(
                $publishers->map( function($publisher){
                    return ['id'=>$publisher->id, 'name'=>$publisher->name];
                })
            );
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([]);
        }
    }
}