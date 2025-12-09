<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category')->latest()->get();
        return Inertia::render('products/Index', compact('products'));
    }
    
    public function create(){
        $categories = Category::all();
        return Inertia::render('products/Create', compact('categories'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:225',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('message', 'Product added successfully');
    }

    public function edit(Product $product){
        $categories = Category::all();
        $product->load('category');
        return Inertia::render('products/Edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'name' => 'required|string|max:225',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
        ]);
        
        return redirect()->route('products.index')->with('message', 'Product updated successfully');
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Product deleted successfully');
    }
}
