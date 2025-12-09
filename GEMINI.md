# Gemini

## Overview

This project is a full-stack Inventory Management web application built using the **VILT Stack (Vue, Inertia, Laravel, Tailwind CSS)**.

## Core Technologies

- Vue.js (v3 Front-end)
- Inertia.js (Adapter)
- Laravel (PHP Backend)
- Tailwind CSS

## Coding Style

- Use **2 spaces** for indentation in all file types (PHP, JS, Vue, HTML/Blade).

## Rules

- The output code must be **minimalist and immediately functional**.
- Do not include advanced error handling, logging, or extensive comments unless explicitly requested. Use direct, imperative logic.

---

## Detailed Style Guide & Examples

### Laravel

#### Routing

Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/products',[ProductController::class, 'index'])->name('products.index');
Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
Route::post('/products',[ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.destroy');
});

#### Controller

class ProductController extends Controller
{
public function index(){
$products = Product::latest()->get();
return Inertia::render('products/Index', compact('products'));
}

    public function create(){
        return Inertia::render('products/Create', []);
    }

    public function store(Request $request){
        $data = $request->validate([
        'name' => 'required|string|max:225',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('message', 'Product added successfully');
    }

    public function edit(Product $product){
        return Inertia::render('products/Edit', compact('product'));
    }

    public function update(Request $request, Product $product){
        $request->validate([
        'name' => 'required|string|max:225',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        ]);

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('products.index')->with('message', 'Product updated successfully');
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Product deleted successfully');
    }

}

#### Model

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'description'];
}


### Pages
sample path: vilt-project\resources\js\pages\products\Index.vue

### Database Tables
Products:
id
name
category_id
price
description
created_at
updated_at

Categories:
id
name
slug
created_at
updated_at

Stocks:
id
product_id
quantity
created_at
updated_at

Suppliers:
id
name
email
phone
address
created_at
updated_at

Purchase_Orders:
id
supplier_id
order_date
status
created_at
updated_at

Purchase_Order_Items:
id
purchase_order_id
product_id
quantity
unit_price
created_at
updated_at

Customers:
id
name
email
phone
address
created_at
updated_at

Sales_Orders:
id
customer_id
order_date
status
created_at
updated_at

Sales_Order_Items:
id
sales_order_id
product_id
quantity
unit_price
created_at
updated_at
