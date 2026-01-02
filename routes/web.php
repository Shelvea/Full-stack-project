<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome_new');
})->name('home');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

//Admin routes group
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function(){
    
    Route::get('/dashboard', [AdminController::class, 'Dashboard'])->name('admin.dashboard');
    //display all products
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    // route to display the form for creating a product
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    //store a product in the products table
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    //Show details of a specific product by ID
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
    //edit
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    //update an existing product
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    //delete an existing product
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    
    //manage orders - display all orders //viewing order page by route to order management page with highlight effect
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    //view delivery details include sharelink of specific order id
    Route::get('/delivery/{orderId}', [OrderController::class, 'delivery'])->name('admin.orders.delivery');
    //delete an existing order
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    //admin update order status
    Route::put('/orders/{orderId}/status', [OrderController::class, 'updateStatus']);

});

//User routes group
Route::prefix('user')->middleware(['auth', 'verified'])->group(function(){
    
    Route::get('/dashboard', [UserController::class, 'Dashboard'])->name('dashboard');//auth middleware is pre-built middleware for check user has login or not, if user has not login it will redirect to login page    

    Route::get('/fruits', [ProductController::class, 'fruits'])->name('products.fruits');

    Route::get('/vegetables', [ProductController::class, 'vegetables'])->name('products.vegetables');

    //cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');// view cart

    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');

    Route::post('/cart/update/{itemId}', [CartController::class, 'ajaxUpdate'])->name('cart.ajaxUpdate');

    Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeItem'])->name('cart.remove');

    //checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');// view checkout
    //place order
    Route::post('/placeOrder', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    //place order success
    Route::get('/placeOrder-success', function(){
        return view('customer.order.success');
    })->name('order.success');

    //show user's order
    Route::get('/my-orders/{status?}', [OrderController::class, 'indexUser'])->name('order.indexUser');

    //show user's payment
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

});

//Route::get('/inertia', function () {
//    return Inertia::render('Welcome');
//});

//Route::get('/users', fn () => view('app'))->name('app');

//Route::get('/users', function(){
//    return view('vue');
//})->name('app');

Route::get('/login', function(){
    return view('login');
})->name('login');

//handle login form
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

//Route for register form
Route::get('/register', function(){
    return view('register');
})->name('register');

//handle register form
Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');

Route::get('/about', function(){
    return view('about');
})->name('aboutPage');

Route::get('/contact', function(){
    return view('contact');
})->name('contactPage');

Route::get('/fruit', function(){
    return view('fruit');
})->name('fruit');

Route::get('/vegetable', function(){
    return view('vegetable');
})->name('vegetable');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SPA fallback route (LAST)
Route::get('/app/{any?}', function () {
    return view('vue'); // your Blade shell
})->where('any', '.*')->name('app');//for SPA Vue route behavior