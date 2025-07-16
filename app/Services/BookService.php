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

        $book = Book::findOrFail( $id);
        
        return $book;
    }

    public function updateBook( int $id, string $title, string $isbn, string $description, string $publication_date, 
                                int $pages, int $total_copies): Book{
        // fetch the book from database by its ID
        $book = Book::findOrFail( $id);

        // update the book informations
        $book->title = $title;
        $book->isbn = $isbn;
        $book->description = $description;
        $book->publication_date = $publication_date;
        $book->number_of_pages = $pages;
        $book->total_copies = $total_copies;
        
        // save changes
        $book->save();

        return $book;
    }

    public function deleteBook( Book $book){
        $book->delete();
    }
    
    public function attachBook( Book $book, array $tags, array $categories, array $authors, array $publishers): void{
        $book->tags()->attach( $tags);
        $book->categories()->attach( $categories);
        $book->authors()->attach( $authors);
        $book->publishers()->attach( $publishers);
    }

    public function syncBook( Book $book, array $tags, array $categories, array $authors, array $publishers): void{
        // this function update elts attached to the book , it keeps just the elements given as argument 
        $book->tags()->sync( $tags);
        $book->categories()->sync( $categories);
        $book->authors()->sync( $authors);
        $book->publishers()->sync( $publishers);
    }

    public function detachBook( Book $book): void{
        $book->tags()->detach();
        $book->categories()->detach();
        $book->authors()->detach();
        $book->publishers()->detach();
    }
}