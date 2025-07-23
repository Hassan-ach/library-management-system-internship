<?php
namespace App\Services;

use App\Models\Publisher;

class PublisherService
{
    public function createPublisher( String $name){
        
        $publisher = Publisher::create([
            'name' => $name
        ]);

        return $publisher; 
    }

    public function getPublisher( int $id){

        $publisher = Publisher::findOrFail( $id);

        return $publisher;
    }

    public function updatePublisher( int $id, array $validated){
        $publisher = $this->getPublisher( $id);
        $publisher->updateOrFail(
            $validated
        );
        $publisher->save();
    }

    public function deletePublisher( int $id){
        $publisher = $this->getPublisher( $id);
        $publisher->deleteOrFail();
    }

    public function createSetOfPublishers( array $names): array{ 
        $publishers = [];
        foreach( $names as $name){
            $publisher = $this->createPublisher( $name);
            
            array_push( $publishers, $publisher);
        }
        
        return $publishers;
    }

    public function getSetOfPublishers( array $ids): array{
        $publishers = [];
        foreach( $ids as $id){
            $publisher = $this->getPublisher( $id);
            array_push( $publishers, $publisher);
        }

        return $publishers;
    }

    public function paginatePublishers(int $num = 10){
        $publishers = Publisher::latest()->paginate( $num);
        return $publishers;
    }

    public function searchPublishers( $query, int $num = 10){
        $publishers = Publisher::where('name', 'like', "%{$query}%")->paginate($num);
        return $publishers;
    }
}
