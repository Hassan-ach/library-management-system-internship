<?php

namespace App\Http\Controllers\Librarian\GoogleApiService;

use App\Http\Controllers\Controller;
use App\Services\GoogleApiService\GoogleBookService;
use Illuminate\Http\Request;

class GoogleApiServiceController extends Controller
{
    protected $googleService;

    // public function __construct(GoogleBookService $googleService)
    // {
    //     $this->googleService = $googleService;
    // }

    public function getBookInfo(Request $request)
    {
        // Validating the request to ensure 'isbn' is present
        $isbn = $request->input('isbn') ? $request->input('isbn') : $request->query('isbn');

        if (! $isbn) {
            return back()->with(['error' => 'The ISBN field is required.']);
        }

        try {
            // Using the service to fetch book info from Google API
            $data = GoogleBookService::getBookInfo($isbn);

            if (! $data) {
                // In case Google API doesn't return valid book data
                return redirect(route('librarian.books.create'))->with(['info'=>'Aucun livre trouvé pour cet ISBN.']);

            }

            return view('librarian.books.form', $data);
        } catch (\Exception $e) {

            return back()->with(['error' => 'An error occurred while getting book info.']);
        }
    }
}
