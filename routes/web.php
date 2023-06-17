<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TshirtImageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

Route::view('/', 'home')->name('root');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes(['verify' => true]);

//Encomendas
Route::get('/encomendas', [OrderController::class, 'index'])->name('orders.index');
Route::get('/encomendas/criar', [OrderController::class, 'create'])->name('orders.create');
Route::get('/encomendas/{order}/editar', [OrderController::class, 'edit'])->name('orders.edit');
Route::get('/encomendas/minhas', [OrderController::class, 'minhasEncomendas'])->name('orders.minhas');
Route::get('/encomendas/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::post('/encomendas', [OrderController::class, 'store'])->name('orders.store');
Route::put('/encomendas/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/encomendas/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

//Clientes
Route::get('/clientes', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/clientes/criar', [CustomerController::class, 'create'])->name('customers.create');
Route::get('/clientes/{customer}/editar', [CustomerController::class, 'edit'])->name('customers.edit');
Route::get('/clientes/{customer}', [CustomerController::class, 'show'])->name('customers.show');

Route::post('/clientes', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/clientes/{customer}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/clientes/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

//Imagens Tshirt
Route::get('/catalogo', [TshirtImageController::class, 'catalogo'])->name('tshirt_images.catalogo');
Route::get('/tshirt_images', [TshirtImageController::class, 'index'])->name('tshirt_images.index');
Route::get('/tshirt_images/criar', [TshirtImageController::class, 'create'])->name('tshirt_images.create');
Route::get('/tshirt_images/{tshirtImage}/editar', [TshirtImageController::class, 'edit'])->name('tshirt_images.edit');
Route::get('/tshirt_images/{tshirtImage}', [TshirtImageController::class, 'show'])->name('tshirt_images.show');
Route::get('/tshirt_images/m/minhas', [TshirtImageController::class, 'minhasTshirtImages'])->name('tshirt_images.minhas');

Route::post('/tshirt_images', [TshirtImageController::class, 'store'])->name('tshirt_images.store');
Route::put('/tshirt_images/{tshirtImage}', [TshirtImageController::class, 'update'])->name('tshirt_images.update');
Route::delete('/tshirt_images/{tshirtImage}', [TshirtImageController::class, 'destroy'])->name('tshirt_images.destroy');

//Preços

Route::get('/precos', [PriceController::class, 'index'])->name('prices.index');
Route::get('/precos/criar', [PriceController::class, 'create'])->name('prices.create');
Route::get('/precos/{price}/editar', [PriceController::class, 'edit'])->name('prices.edit');

Route::post('/precos', [PriceController::class, 'store'])->name('prices.store');
Route::put('/precos/{price}', [PriceController::class, 'update'])->name('prices.update');
Route::delete('/precos/{price}', [PriceController::class, 'destroy'])->name('prices.destroy');

//Categorias

Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categorias/criar', [CategoryController::class, 'create'])->name('categories.create');
Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categories.edit');
Route::get('/categorias/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

//Cores

Route::get('/cores', [ColorController::class, 'index'])->name('colors.index');
Route::get('/cores/criar', [ColorController::class, 'create'])->name('colors.create');
Route::get('/cores/{code}/editar', [ColorController::class, 'edit'])->name('colors.edit');
Route::get('/cores/{code}', [ColorController::class, 'show'])->name('colors.show');

Route::post('/cores', [ColorController::class, 'store'])->name('colors.store');
Route::put('/cores/{color}', [ColorController::class, 'update'])->name('colors.update');
Route::delete('/cores/{color}', [ColorController::class, 'destroy'])->name('colors.destroy');

//Itens de Encomenda

Route::get('/orderItems', [OrderItemController::class, 'index'])->name('orderItems.index');
Route::get('/orderItems/criar', [OrderItemController::class, 'create'])->name('orderItems.create');
Route::get('/orderItems/{orderItem}/editar', [OrderItemController::class, 'edit'])->name('orderItems.edit');
Route::get('/orderItems/{orderItem}', [OrderItemController::class, 'show'])->name('orderItems.show');

Route::post('/orderItems', [OrderItemController::class, 'store'])->name('orderItems.store');
Route::put('/orderItems/{orderItem}', [OrderItemController::class, 'update'])->name('orderItems.update');
Route::delete('/orderItems/{orderItem}', [OrderItemController::class, 'destroy'])->name('orderItems.destroy');

//Cart 

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Remove a "orderItem"(Tshirt) from the cart:
Route::delete('/cart/{tshirtUniqueId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
// Show the cart:
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
// Confirm (store) the cart and save orderItem/tshirts registration on the database:
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
// Clear the cart:
Route::delete('/cart', [CartController::class, 'destroy'])->name('cart.destroy');

