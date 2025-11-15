<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\Category\CategoryData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {}

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CreateCategoryRequest $request)
    {
        $categoryData = CategoryData::fromRequest($request);
        $this->categoryService->createCategory($categoryData);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $categoryData = CategoryData::fromRequest($request);
        $this->categoryService->updateCategory($category, $categoryData);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}