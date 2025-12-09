<?php


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProductController;

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
});


require __DIR__.'/settings.php';
