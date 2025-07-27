<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;


class LibrarianDashboardController extends Controller
{
    public function index(){
        try{
        // books statistics
        $total_books = count_total_books();
        $non_available_books = get_non_available_books();
        $available_books = $total_books - $non_available_books;

        // book requests statistics
        $request_statistics = get_requests_statics();
        
        // last 8 requests (for quick start part)
        $requests = BookRequest::with('latestRequestInfo')->orderBy('created_at', 'desc')
                    ->take(8)->get();

        
        return view('librarian.dashboard',  compact(
                        ['total_books',
                       'non_available_books',
                       'available_books',
                       'request_statistics',
                       'requests']));
        }catch(\Throwable $th){
            return $th;
            //return view('auth.login')->with('error', 'Une erreur s\'est produit lors de chargement de Tableau de board');
        }
    }
}