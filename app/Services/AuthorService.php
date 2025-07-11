<?php 
namespace App\Services;
use App\Models\Author;

class AuthorService
{
    public function createAuthor(string $first_name, string $last_name): Author{
        $author = Author::create([
            'first_name' => $first_name,
            'last_name' => $last_name
        ]);

        return $author;
    }

    public function getAuthor(int $id): Author{

        $author = Author::find( $id);
        
        return $author;
    }

}