<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Http\Middleware\IsAdmin;

/* ---------- Гостьові (login / register) ---------- */
Route::get('/',         [AuthController::class,'showLogin'])->name('root');
Route::get('/login',    [AuthController::class,'showLogin'])->name('login');
Route::post('/login',   [AuthController::class,'login'])->name('login.post');
Route::get('/register', [AuthController::class,'showRegister'])->name('register');
Route::post('/register',[AuthController::class,'register'])->name('register.post');
Route::post('/logout',  [AuthController::class,'logout'])->name('logout');

/* ---------- Усі залогінені користувачі ---------- */
Route::middleware(EnsureUserIsAuthenticated::class)->group(function () {

    /* --- Каталог --- */
    Route::get('/catalog', [ProductController::class,'index'])->name('catalog');

    /* --- Корзина --- */
    Route::get('/cart',                [CartController::class,'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class,'add'])->whereNumber('product')->name('cart.add');
    Route::put('/cart/{cartItem}',     [CartController::class,'update'])->whereNumber('cartItem')->name('cart.update');
    Route::delete('/cart/{cartItem}',  [CartController::class,'remove'])->whereNumber('cartItem')->name('cart.remove');
    Route::post('/cart/clear',         [CartController::class,'clear'])->name('cart.clear');

    /* --- Оформлення --- */
    Route::get('/checkout',  [CheckoutController::class,'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class,'process'])->name('checkout.process');

    /* --- Адмін-CRUD (IsAdmin) --- */
    Route::middleware(IsAdmin::class)->group(function () {

        /* Продукти */
        Route::get('/products/create',        [ProductController::class,'create'])->name('products.create');
        Route::post('/products',              [ProductController::class,'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->whereNumber('product')->name('products.edit');
        Route::put('/products/{product}',     [ProductController::class,'update'])->whereNumber('product')->name('products.update');
        Route::delete('/products/{product}',  [ProductController::class,'destroy'])->whereNumber('product')->name('products.destroy');

        /* Категорії */
        Route::get('/categories/create',         [CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories',               [CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->whereNumber('category')->name('categories.edit');
        Route::put('/categories/{category}',     [CategoryController::class,'update'])->whereNumber('category')->name('categories.update');
        Route::delete('/categories/{category}',  [CategoryController::class,'destroy'])->whereNumber('category')->name('categories.destroy');
    });
    
    Route::get('/products/{product}', [ProductController::class,'show'])->whereNumber('product')->name('products.show');
});
