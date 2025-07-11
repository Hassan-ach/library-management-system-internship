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

}