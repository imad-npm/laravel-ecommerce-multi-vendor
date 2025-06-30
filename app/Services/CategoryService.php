<?php

namespace App\Services;

use App\DataTransferObjects\CategoryData;
use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function createCategory(CategoryData $data): Category
    {
        return Category::create($data->toArray());
    }

    public function updateCategory(Category $category, CategoryData $data): bool
    {
        return $category->update($data->toArray());
    }

    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }
}
