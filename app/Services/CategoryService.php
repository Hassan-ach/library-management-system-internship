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

}