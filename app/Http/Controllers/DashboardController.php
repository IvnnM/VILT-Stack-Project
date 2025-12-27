<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Stock::sum('quantity');
        $lowStockItems = Stock::where('quantity', '<=', 10)->with('product')->get();

        return Inertia::render('Dashboard', [
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'lowStockItems' => $lowStockItems,
        ]);
    }
}
