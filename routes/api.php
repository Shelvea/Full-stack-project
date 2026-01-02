<?php

use App\Http\Controllers\LalamoveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//lalamove estimate delivery fee and distance
Route::post('/lalamove/estimate', [LalamoveController::class, 'estimate'])->name('lalamove.estimate');//incorrect, no named route in api.php

// This API request is authenticated
Route::middleware('auth:sanctum')->group(function () {//protected endpoint api using middleware auth:sanctum, who is this user come from ?
        
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', [UserController::class, 'index']); //list all user

    Route::delete('/users/{id}', [UserController::class, 'destroy']); //delete user

    Route::get('/notifications', [NotificationController::class, 'index']);

    //delete notification
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    //mark as read view order-placed details in notification
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read']);


});