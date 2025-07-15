<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\AuthorService;
use App\Services\BookService;
use App\Services\CategoryService;
use App\Services\PublisherService;
use App\Services\TagService;
class Services
{
    
    private  $book_service;
    private  $category_service;
    private  $tag_service;
    private  $author_service;
    private  $publisher_service;
    
    public function __construct(BookService $book_service, CategoryService $category_service, TagService $tag_service,
                                AuthorService $author_service, PublisherService $publisher_service)
    {
        $this->book_service = $book_service;
        $this->category_service = $category_service;
        $this->tag_service = $tag_service;
        $this->author_service = $author_service;
        $this->publisher_service = $publisher_service;
    }
    
    public function createBook(array $data){
        DB::beginTransaction();
        
        try{
            // Create a book instance
            $book = $this->book_service->createBook(
                $data['title'],
                $data['isbn'],
                $data['description'],
                $data['publication_date'],
                $data['number_of_pages'],
                $data['total_copies']
            );

            // Create new items (category/publisher/author/tag) and retrieve existing ones
            $tags = array_merge(
                $this->tag_service->createSetOfTags( $data['tags']['new']),
                $this->tag_service->getSetOfTags( $data['tags']['old'])
            );

            $publishers = array_merge(
                $this->publisher_service->createSetOfPublishers( $data['publishers']['new']),
                $this->publisher_service->getSetOfPublishers( $data['publishers']['old'])
            );

            $categories = array_merge(
                $this->category_service->createSetOfCategories( $data['categories']['new']),
                $this->category_service->getSetOfCategories( $data['categories']['old'])
            );
            
            $authors = array_merge(
                $this->author_service->createSetOfAuthors( $data['authors']['new']),
                $this->author_service->getSetOfAuthors( $data['authors']['old'])
            );

            // Link/attach book object and its items
            $this->book_service->attachBook( $book, $tags, $categories,
                                                     $authors, $publishers);

            DB::commit();

            return $book;

        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function updateBook(array $data){
        DB::beginTransaction();
        try{
            $book = $this->book_service->updateBook(
                $data['book_id'],
                $data['title'],
                $data['isbn'],
                $data['description'],
                $data['publication_date'],
                $data['number_of_pages'],
                $data['total_copies']
            );

            $tags = array_merge(
                $this->tag_service->createSetOfTags( $data['tags']['new']),
                $this->tag_service->getSetOfTags( $data['tags']['old'])
            );

            $publishers = array_merge(
                $this->publisher_service->createSetOfPublishers( $data['publishers']['new']),
                $this->publisher_service->getSetOfPublishers( $data['publishers']['old'])
            );

            $categories = array_merge(
                $this->category_service->createSetOfCategories( $data['categories']['new']),
                $this->category_service->getSetOfCategories( $data['categories']['old'])
            );
            
            $authors = array_merge(
                $this->author_service->createSetOfAuthors( $data['authors']['new']),
                $this->author_service->getSetOfAuthors( $data['authors']['old'])
            );

            $this->book_service->syncBook( $book, $tags, $categories, 
                                                    $authors, $publishers);

            DB::commit();

            return $book;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

    }

    public function deleteBook(array $data)
    {
        DB::beginTransaction();
        try{
            $book = $this->book_service->getBook( $data['book_id']);
            
            $this->book_service->detachBook( $book);
            
            $this->book_service->deleteBook( $book);

            DB::commit();

        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

}