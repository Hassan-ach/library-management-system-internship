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

}

/*$main = new PublisherService();
$pub = $main->createPublisher("chark_edition");
$pub2 = PublisherService::getPublisher(1);
echo $pub2->name;*/