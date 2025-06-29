<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return back()->with('success', 'Категорію створено');
    }
    public function create()
    {
        return view('categories.create');
    }
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('catalog')
                         ->with('success', 'Категорію оновлено');
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Категорію видалено');
    }
}
