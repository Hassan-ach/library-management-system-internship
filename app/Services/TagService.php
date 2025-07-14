<?php 
namespace App\Services;
use App\Models\Tag;

class TagService
{
    public function createTag( string $label): Tag{
        $tag = Tag::create([
            'label' => $label
        ]);

        return $tag;
    }

    public function getTag(int $id): Tag{

        $tag = Tag::find( $id);
        
        return $tag;
    }

    public function deleteTag( Tag $tag){
        $tag->delete();
    }

    public function createSetOfTags( array $labels): array{ 
        $tags = [];
        foreach( $labels as $label){
            $tag = $this->createTag( $label);
            array_push( $tags, $tag);
        }
        
        return $tags;
    }

    public function getSetOfTags( array $ids): array{
        $tags = [];
        foreach( $ids as $id){
            $tag = $this->getTag( $id);
            array_push( $tags, $tag);
        }

        return $tags;
    }

    public function deleteSetOfTags( array $tags){
        try{
            foreach( $tags as $tag){
                $this->deleteTag( $tag);
            }
        }catch(\Exception $e){
            echo 'This tag cann\'t be deleted, it is maybe attached to another object';
        }       
    }
}