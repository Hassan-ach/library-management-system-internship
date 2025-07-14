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

    public function deleteAuthor( Author $author){
        $author->delete();
    }

    public function createSetOfAuthors( array $data): array{ 
        // passed array should be formated as $data = [['first_name','last_name'], ....] 
        $authors = [];
        foreach( $data as $item){
            [ $first_name, $last_name] = $item; 
            $author = $this->createAuthor( $first_name, $last_name);
            
            array_push( $authors, $author);
        }
        
        return $authors;
    }

    public function getSetOfAuthors( array $ids): array{
        $authors = [];
        foreach( $ids as $id){
            $author = $this->getAuthor( $id);
            array_push( $authors, $author);
        }

        return $authors;
    }

    public function deleteSetOfAuthors( array $authors){
        try{
            foreach( $authors as $author){
                $this->deleteAuthor( $author);
            }
        }catch(\Exception $e){
            echo 'This Author cann\'t be deleted, it is maybe attached to another object';
        }       
    }
}