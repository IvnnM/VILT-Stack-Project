<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')->latest()->get();
        return Inertia::render('stocks/Index', compact('stocks'));
    }

    public function create()
    {
        $products = Product::all();
        return Inertia::render('stocks/Create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        Stock::create($data);

        return redirect()->route('stocks.index')->with('message', 'Stock entry created or updated successfully');
    }

    public function edit(Stock $stock)
    {
        $products = Product::all();
        $stock->load('product');
        return Inertia::render('stocks/Edit', compact('stock', 'products'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->update([
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
        ]);

        return redirect()->route('stocks.index')->with('message', 'Stock updated successfully');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('message', 'Stock deleted successfully');
    }
}
