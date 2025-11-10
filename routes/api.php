<?php

use App\Http\Controllers\LalamoveController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//lalamove estimate delivery fee and distance
Route::post('/lalamove/estimate', [LalamoveController::class, 'estimate'])->name('lalamove.estimate');