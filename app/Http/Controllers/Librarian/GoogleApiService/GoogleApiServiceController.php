<?php

namespace App\Http\Controllers\Librarian\GoogleApiService;

use App\Http\Controllers\Controller;
use App\Services\GoogleApiService\GoogleBookService;
use Illuminate\Http\Request;

class GoogleApiServiceController extends Controller
{
    protected $googleService;

    public function __construct(GoogleBookService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function getBookInfo(Request $request)
    {
        // Validating the request to ensure 'isbn' is present
        $isbn = $request->input('isbn') ?? $request->query('isbn');

        if (! $isbn) {
            return back()->withErrors(['isbn' => 'The ISBN field is required.']);
        }

        try {
            // Using the service to fetch book info from Google API
            $response = $this->googleService->getBookInfo($isbn);

            if (! $response) {
                // In case Google API doesn't return valid book data
                return view(
                    'librarian.books.create',
                    ['error' => 'No book found for this ISBN.'],
                );
            }

            return view('librarian.books.form', compact('response'));
        } catch (\Exception $e) {

            return back()->withErrors(['error' => 'An error occurred while getting book info.']);
        }
    }
}
