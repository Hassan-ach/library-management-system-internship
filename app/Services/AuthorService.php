<?php 
namespace App\Services;
use App\Models\Author;

class AuthorService
{
    public function createAuthor(string $name): Author{
        $author = Author::create([
            'name' => $name
        ]);

        return $author;
    }

    public function getAuthor(int $id): Author{

        $author = Author::findOrFail( $id);
        
        return $author;
    }

    public function updateAuthor(int $id, string $name) {
        $author = $this->getAuthor( $id);
        $author->name = $name;
        $author->save();
    }

    public function deleteAuthor( int $id){
        $author = $this->getAuthor( $id);
        $author->delete();
    }

    public function createSetOfAuthors( array $names): array{ 
        // passed array should be formated as $names = ['publisher1_name', 'publisher2_name', ....] 
        $authors = [];
        foreach( $names as $name){
            $author = $this->createAuthor( $name);
            
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
    
    public function paginateAuthors(int $num = 10){
        $authors = Author::latest()->paginate( $num);
        return $authors;
    }

    public function searchAuthors( $query, int $num = 10){
        $authors = Author::where('name', 'like', "%{$query}%")->paginate($num);
        return $authors;
    }
}