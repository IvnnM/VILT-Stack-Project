<?php


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products',[ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
    Route::post('/products',[ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories',[App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create',[App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories',[App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit',[App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}',[App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',[App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/stocks',[App\Http\Controllers\StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/create',[App\Http\Controllers\StockController::class, 'create'])->name('stocks.create');
    Route::post('/stocks',[App\Http\Controllers\StockController::class, 'store'])->name('stocks.store');
    Route::get('/stocks/{stock}/edit',[App\Http\Controllers\StockController::class, 'edit'])->name('stocks.edit');
    Route::put('/stocks/{stock}',[App\Http\Controllers\StockController::class, 'update'])->name('stocks.update');
    Route::delete('/stocks/{stock}',[App\Http\Controllers\StockController::class, 'destroy'])->name('stocks.destroy');

    Route::get('/suppliers',[SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create',[SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers',[SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit',[SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}',[SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}',[SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
});


require __DIR__.'/settings.php';
