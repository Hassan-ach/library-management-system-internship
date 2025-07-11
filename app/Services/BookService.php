<?php
namespace App\Services;

use App\Models\Book;

class BookService
{
    public function createBook( string $title, string $isbn, string $description, string $publication_date, 
                                int $pages, int $total_copies): Book{
        
        $book = Book::create([
            'title' => $title,
            'isbn' => $isbn,
            'description' => $description,
            'publication_date' => $publication_date,  // date should be formated as YYYY-MM-DD
            'number_of_pages' => $pages,
            'total_copies' => $total_copies
        ]);

        return $book;
    }

    public function getBook(int $id): Book{

        $book = Book::find( $id);
        
        return $book;
    }


}