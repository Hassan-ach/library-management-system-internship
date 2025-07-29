<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Services
{
    private $book_service;

    private $category_service;

    private $tag_service;

    private $author_service;

    private $publisher_service;

    public function __construct(BookService $book_service, CategoryService $category_service, TagService $tag_service,
        AuthorService $author_service, PublisherService $publisher_service)
    {
        $this->book_service = $book_service;
        $this->category_service = $category_service;
        $this->tag_service = $tag_service;
        $this->author_service = $author_service;
        $this->publisher_service = $publisher_service;
    }

    public function getBookData($id): array
    {
        $book = $this->book_service->getBook($id);

        $old_tags = [];
        foreach ($book->tags as $tag) {
            array_push($old_tags, ['id' => $tag->id, 'name' => $tag->label]);
        }
        $old_categories = [];
        foreach ($book->categories as $cat) {
            array_push($old_categories, ['id' => $cat->id, 'name' => $cat->label]);
        }
        $old_publishers = [];
        foreach ($book->publishers as $publisher) {
            array_push($old_publishers, ['id' => $publisher->id, 'name' => $publisher->name]);
        }
        $old_authors = [];
        foreach ($book->authors as $author) {
            array_push($old_authors, ['id' => $author->id, 'name' => $author->name]);
        }

        $book_data = [
            'title' => $book->title,
            'isbn' => $book->isbn,
            'image_url' => $book->image_url,
            'publication_date' => $book->publication_date->format('Y-m-d'),
            'number_of_pages' => $book->number_of_pages,
            'total_copies' => $book->total_copies,
            'available_copies' => $book->total_copies - get_borrowed_copies($book->id),
            'description' => $book->description,
            'tags' => [
                'old' => $old_tags,
                'new' => [],
            ],
            'categories' => [
                'old' => $old_categories,
                'new' => [],
            ],
            'publishers' => [
                'old' => $old_publishers,
                'new' => [],
            ],
            'authors' => [
                'old' => $old_authors,
                'new' => [],
            ],
        ];

        return $book_data;
    }

    public function createBook(array $data)
    {
        DB::beginTransaction();

        try {
            // Create a book instance
            $book = $this->book_service->createBook(
                $data['title'],
                $data['isbn'],
                $data['image_url'],
                $data['description'],
                $data['publication_date'],
                $data['number_of_pages'],
                $data['total_copies']
            );

            // Create new items (category/publisher/author/tag) and retrieve existing ones
            $tags = array_merge(
                $this->tag_service->createSetOfTags($data['tags']['new']),
                $this->tag_service->getSetOfTags($data['tags']['old'])
            );

            $publishers = array_merge(
                $this->publisher_service->createSetOfPublishers($data['publishers']['new']),
                $this->publisher_service->getSetOfPublishers($data['publishers']['old'])
            );

            $categories = array_merge(
                $this->category_service->createSetOfCategories($data['categories']['new']),
                $this->category_service->getSetOfCategories($data['categories']['old'])
            );

            $authors = array_merge(
                $this->author_service->createSetOfAuthors($data['authors']['new']),
                $this->author_service->getSetOfAuthors($data['authors']['old'])
            );

            // Link/attach book object and its items
            $this->book_service->attachBook($book, $tags, $categories,
                $authors, $publishers);

            DB::commit();

            return $book;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateBook(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $book = $this->book_service->updateBook(
                $id,
                $data['title'],
                $data['isbn'],
                $data['description'],
                $data['publication_date'],
                $data['number_of_pages'],
                $data['total_copies']
            );

            $tags = array_merge(
                $this->tag_service->createSetOfTags($data['tags']['new']),
                $this->tag_service->getSetOfTags($data['tags']['old'])
            );

            $publishers = array_merge(
                $this->publisher_service->createSetOfPublishers($data['publishers']['new']),
                $this->publisher_service->getSetOfPublishers($data['publishers']['old'])
            );

            $categories = array_merge(
                $this->category_service->createSetOfCategories($data['categories']['new']),
                $this->category_service->getSetOfCategories($data['categories']['old'])
            );

            $authors = array_merge(
                $this->author_service->createSetOfAuthors($data['authors']['new']),
                $this->author_service->getSetOfAuthors($data['authors']['old'])
            );

            $this->book_service->syncBook($book, $tags, $categories,
                $authors, $publishers);

            DB::commit();

            return $book;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function deleteBook(int $bookId)
    {
        DB::beginTransaction();
        try {
            $book = $this->book_service->getBook($bookId);

            $this->book_service->detachBook($book);

            $this->book_service->deleteBook($book);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
