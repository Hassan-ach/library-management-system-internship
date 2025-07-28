<?php

namespace App\Services\GoogleApiService;

use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class GoogleBookService
{
    public static function getBookInfo(string $isbn)
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
            'title' => $item['title'] ?? '',
            'isbn' => $isbn,
            'description' => $item['description'] ?? '',
            'publication_date' => Carbon::createFromFormat('Y', $item['publishedDate'] ?? null)->format('Y-m-d'),
            'number_of_pages' => $item['pageCount'] ?? null,
            'total_copies' => 1, // Default value as in original
            'image_url' => $item['imageLinks']['thumbnail'] ?? null,
        ];

        // Initialize arrays
        $tags = ['old' => [], 'new' => []];
        $categories = ['old' => [], 'new' => []];
        $authors = ['old' => [], 'new' => []];
        $publishers = ['old' => [], 'new' => []];

        // Process categories from API response
        if (isset($item['categories']) && is_array($item['categories'])) {
            // Get all category labels from API
            $categoryLabels = $item['categories'];

            // Check which categories exist in database (assuming you have a Category model)
            $existingCategories = Category::whereIn('label', $categoryLabels)->get();
            $existingCategoryLabels = $existingCategories->pluck('label')->toArray();

            // Add existing categories to 'old' array
            foreach ($existingCategories as $category) {
                // $categories['old'][] = [
                //     'id' => $category->id,
                //     'label' => $category->label,
                //     'description' => $category->description ?? '',
                // ];
                array_push($categories['old'], [$category->id, $category->label]);
            }

            // Add new categories to 'new' array
            foreach ($categoryLabels as $label) {
                if (! in_array($label, $existingCategoryLabels)) {
                    // $categories['new'][] = [
                    //     'label' => $label,
                    //     'description' => '', // Empty for now; can be filled later
                    // ];
                    array_push($categories['new'], [$label, 'no descritpion']);

                }
            }
        }

        // Process authors from API response
        if (isset($item['authors']) && is_array($item['authors'])) {
            // Get all author names from API
            $authorNames = $item['authors'];

            // Check which authors exist in database (assuming you have an Author model)
            $existingAuthors = Author::whereIn('name', $authorNames)->get();
            $existingAuthorNames = $existingAuthors->pluck('name')->toArray();

            // Add existing authors to 'old' array
            foreach ($existingAuthors as $author) {
                $authors['old'][] = [
                    'id' => $author->id,
                    'name' => $author->name,
                ];
            }

            // Add new authors to 'new' array
            foreach ($authorNames as $name) {
                if (! in_array($name, $existingAuthorNames)) {
                    // $authors['new'][] = [
                    //     'name' => $name,
                    // ];
                    array_push($authors['new'], $name);
                }
            }
        }

        // Process publisher from API response
        if (isset($item['publisher'])) {
            $publisherName = $item['publisher'];

            // Check if publisher exists in database (assuming you have a Publisher model)
            $existingPublisher = Publisher::where('name', $publisherName)->first();

            if ($existingPublisher) {
                // Add existing publisher to 'old' array
                $publishers['old'][] = [
                    'id' => $existingPublisher->id,
                    'name' => $existingPublisher->name,
                ];
            } else {
                // Add new publisher to 'new' array
                // $publishers['new'][] = [
                //     'name' => $publisherName,
                // ];
                array_push($publishers['new'], $publisherName);
            }
        }

        // Process tags - Since Google Books API doesn't provide tags,
        // we'll leave this empty for now, but maintain the structure
        // You can add custom logic here if you want to generate tags
        // based on categories or other book information

        // Return structured data to match validation requirements
        return [
            'title' => $book['title'],
            'isbn' => $book['isbn'],
            'description' => $book['description'],
            'publication_date' => $book['publication_date'],
            'number_of_pages' => $book['number_of_pages'],
            'total_copies' => $book['total_copies'],
            'image_url' => $book['image_url'],
            'tags' => $tags,
            'categories' => $categories,
            'authors' => $authors,
            'publishers' => $publishers,
        ];
    }
}
