<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function apiSearch(Request $request)
    {
        try {
            $query = $request->input('q');
            $categories = Category::where('label', 'LIKE', "%{$query}%")
                   ->limit(7)
                   ->get();

            return response()->json(
                $categories->map( function($cat){
                    return ['id'=>$cat->id, 'label'=>$cat->label];
                })
            );
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([]);
        }
    }
}