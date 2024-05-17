<?php

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/auth',function () {
    return response()->json([
    'user' => auth()->user(),
]);})
    ->middleware('auth:sanctum');



    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/cart/add/{productId}', [CartController::class, 'addProductToCart']);
        Route::put('/cart/update/{rowId}', [CartController::class, 'updateCart']);
        Route::delete('/cart/remove/{rowId}', [CartController::class, 'removeProductFromCart']);
        Route::get('/cart', [CartController::class, 'viewCart']);
        Route::delete('/cart/destroy', [CartController::class, 'destroyCart']);
        Route::get('/cart/total', [CartController::class, 'getTotal']);
        
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
    });

require __DIR__.'/auth.php';
