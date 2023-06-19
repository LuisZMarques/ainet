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
use App\Http\Controllers\UserController;

Route::view('/', 'home')->name('root');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes(['verify' => true]);

//Encomendas
Route::resource('orders', OrderController::class);
Route::get('/encomendas/minhas', [OrderController::class, 'minhasEncomendas'])->name('orders.minhas');

//Clientes
Route::resource('customers', CustomerController::class);

//Utilizadores
Route::resource('users', UserController::class);

//Imagens Tshirt
Route::get('/catalogo', [TshirtImageController::class, 'catalogo'])->name('tshirt_images.catalogo');
Route::get('/tshirt_images/m/minhas', [TshirtImageController::class, 'minhasTshirtImages'])->name('tshirt_images.minhas');
Route::resource('tshirt_images', TshirtImageController::class);

//Preços
Route::resource('prices', PriceController::class);

//Categorias
Route::resource('categories', CategoryController::class);

//Cores
Route::resource('colors', ColorController::class);

//Itens de Encomenda
Route::resource('orderItems', OrderItemController::class);

//Cart
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/{tshirtUniqueId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart', [CartController::class, 'destroy'])->name('cart.destroy');
