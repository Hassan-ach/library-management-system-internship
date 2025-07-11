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

        $publisher = Publisher::find( $id);

        return $publisher;
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
}
