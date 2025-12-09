<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::latest()->get();
        return Inertia::render('categories/Index', compact('categories'));
    }
    
    public function create(){
        return Inertia::render('categories/Create', []);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:225',
            'slug' => 'required|string|max:255|unique:categories',
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('message', 'Category added successfully');
    }

    public function edit(Category $category){
        return Inertia::render('categories/Edit', compact('category'));
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'name' => 'required|string|max:225',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
        ]);
        
        return redirect()->route('categories.index')->with('message', 'Category updated successfully');
    }

    public function destroy(Category $category){
        $category->delete();
        return redirect()->route('categories.index')->with('message', 'Category deleted successfully');
    }
}
