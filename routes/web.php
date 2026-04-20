<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 

Route::get('/', function () {
    return view('welcome_new');
})->name('home');

require __DIR__.'/auth.php';

//Admin routes group
Route::prefix('admin')->middleware(['auth:sanctum', 'verified','is_admin'])->group(function(){
    
    //display all products
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    // route to display the form for creating a product
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    //store a product in the products table
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    //Show details of a specific product by ID
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('admin.products.show');
    //edit
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    //update an existing product
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    //delete an existing product
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    
    //manage orders - display all orders //viewing order page by route to order management page with highlight effect
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    //view delivery details include sharelink of specific order id
    Route::get('/delivery/{orderId}', [OrderController::class, 'delivery'])->name('admin.orders.delivery');
    //delete an existing order
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    //admin update order status
    Route::put('/orders/{orderId}/status', [OrderController::class, 'updateStatus']);
    //preview customer dashboard
    Route::post('/previewCustomerDashboard', [AdminController::class, 'viewCustomerDashboard'])->name('admin.previewCustomerDashboard');
    //exit from preview customer dashboard
    Route::post('/exitPreviewCustomerDashboard', [AdminController::class, 'exitCustomerPreview'])->name('admin.exitCustomerDashboard');
});

//User routes group
Route::prefix('user')->middleware(['auth:sanctum', 'verified'])->group(function(){

    //Route::get('/fruits', [CustomerProductController::class, 'fruits'])->name('products.fruits');

    //Route::get('/vegetables', [CustomerProductController::class, 'vegetables'])->name('products.vegetables');

    //cart
    //Route::get('/cart', [CartController::class, 'index'])->name('cart.index');// view cart

    //Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');

    Route::post('/cart/update/{itemId}', [CartController::class, 'ajaxUpdate'])->name('cart.ajaxUpdate');

    //Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeItem'])->name('cart.remove');

    //checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');// view checkout
    //place order
    //Route::post('/placeOrder', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    //place order success
    Route::get('/placeOrder-success', function(){
        return view('customer.order.success');
    })->name('order.success');

    //show user's order
    Route::get('/my-orders/{status?}', [OrderController::class, 'indexUser'])->name('order.indexUser');

    //show user's payment
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

});


//Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

//handle logout
Route::post('/logout', [AuthController::class, 'logout'])->name('handle.logout')->middleware(['auth:sanctum','verified']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


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

Route::middleware('auth:sanctum')->group(function () {
    /* These routes are related to user profile management. */
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePass'])->name('profile.password');
    
});

// SPA fallback route (LAST)
Route::get('/app/{any?}', function () {
    return view('vue'); // your Blade shell
})->where('any', '.*')->name('app');//for SPA Vue route behavior

