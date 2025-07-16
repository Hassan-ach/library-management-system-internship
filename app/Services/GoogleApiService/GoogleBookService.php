<?php

namespace App\Services\GoogleApiService;

use Illuminate\Support\Facades\Http;

class GoogleBookService
{
    public function getBookInfo(string $isbn)
    {
        // Send a GET request to the Google Books API using the ISBN
        $response = Http::get(config('services.google-book.url').$isbn);

        // Return null if the request fails (non-200 status code)
        if (! $response->successful()) {
            return null;
        }

        // Decode the JSON response
        $data = $response->json();

        // Check if the 'items' key exists and has at least one book
        if (! isset($data['items']) || count($data['items']) === 0) {
            return null;
        }

        // Extract the first book's volume info
        $item = $data['items'][0]['volumeInfo'];

        // Build the main book info array
        $book = [
            'title' => $item['title'],
            'isbn' => $isbn,
            'description' => $item['description'],
            'publication_date' => $item['publishedDate'],
            'number_of_pages' => $item['pageCount'],
            'total_copies' => 1,
            'average_rating' => $item['averageRating'],
            'image_link' => $item['imageLinks']['thumbnail'],
        ];

        // Build an array of categories (genres)
        $category = [];
        foreach ($item['categories'] as $label) {
            $category[] = [
                'label' => $label,
                'description' => '', // Empty for now; can be filled later
            ];
        }

        // Build an array of authors
        $authors = [];
        foreach ($item['authors'] as $name) {
            $authors[] = [
                'name' => $name,
            ];
        }

        // Get the publisher name
        $publisher = $item['publisher'];

        // Return structured data to be used for creating records in the DB
        return [
            'book' => $book,
            'category' => $category,
            'publisher' => $publisher,
            'authors' => $authors,
        ];
    }
}
