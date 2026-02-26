<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/products');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/register', [ProductController::class, 'register'])->name('products.register'); // 登録画面
Route::post('/products/register', [ProductController::class, 'store'])->name('products.store');   // 登録処理

Route::get('/products/detail/{id}', [ProductController::class, 'detail'])->name('products.detail');

Route::post('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');

Route::post('/products/{id}/delete', [ProductController::class, 'delete'])->name('products.delete');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');