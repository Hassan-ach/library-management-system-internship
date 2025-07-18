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

        $tag = Tag::findOrFail( $id);
        
        return $tag;
    }

    public function updateTag( int $id, $validated){
        $tag = $this->getTag( $id);
        $tag->updateOrFail(
            $validated
        );
        $tag->save();
    }

    public function deleteTag( int $id){
        $tag = $this->getTag( $id);
        $tag->deleteOrFail();
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

    public function paginateTags(int $num = 10){
        $tags = Tag::latest()->paginate( $num);
        return $tags;
    }

    public function searchTags( $query, int $num = 10){
        $tags = Tag::where('label', 'like', "%{$query}%")->paginate($num);
        return $tags;
    }
}