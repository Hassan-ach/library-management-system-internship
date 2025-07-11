<?php 
namespace App\Services;
use App\Models\Category;

class CategoryService
{
    public function createCategory(string $label, string $description): Category{
        $category = Category::create([
            'label' => $label,
            'description' => $description
        ]);

        return $category;
    }

    public function getCategory(int $id): Category{

        $category = Category::find( $id);
        
        return $category;
    }

    public function createSetOfCategories( array $data): array{ 
        // passed array should be formated as $data = [['label_1','desc_1'], ....] 
        $categories = [];
        foreach( $data as $item){
            [$label , $desc] = $item; // $item = ['label_n', 'description_n']
            $category = $this->createCategory( $label, $desc);
            
            array_push( $categories, $category);
        }
        
        return $categories;
    }

    public function getSetOfCategories( array $ids): array{
        $categories = [];
        foreach( $ids as $id){
            $category = $this->getCategory( $id);
            array_push( $categories, $category);
        }

        return $categories;
    }
   

}