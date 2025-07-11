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
}